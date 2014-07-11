<?php
  if(!isset($id)) {
    header("HTTP/1.0 404 Not Found");
    die();
  }

  $stmt = $mysql->prepare("SELECT * FROM `prefs` WHERE `id` = ?");
  $stmt->bind_param("i", $id);
  $stmt->execute();
  $result = getResult($stmt)[0];
  $stmt->close();

  function filterBasic($prefsName, $dbName, $query, $table="schools") {
    global $result;
    switch($result[$prefsName . "_type"]) {
      case 'none':
      default:
        $query .= "TRUE";
        break;
      case 'some':
        foreach(explode(",", $result[$prefsName]) as $item) {
          $query .= "`" . $table . "`.`" . $dbName . "` = " . $item . " OR "; // TODO: watch for SQL injection
        }
        $query = substr($query, 0, strlen($query) - 4);
        break;
    }
    return $query;
  }

  function filterOption($prefsName, $dbName, $query, $table="schools") {
    global $result;
    switch($result[$prefsName]) {
      case -1:
      default:
        $query .= "TRUE";
        break;
      case 0:
      case 1:
        $query .= "`" . $table . "`.`" . $dbName . "` = " . $result[$prefsName];
        break;
    }
    return $query;
  }

  function filterOption2($prefsName, $dbName, $query, $table="schools") {
    global $result;
    switch($result[$prefsName]) {
      case 0:
      default:
        $query .= "`" . $table . "`.`" . $dbName . "` > 0";
        break;
      case 1:
      case 2:
      case 3:
        $query .= "`" . $table . "`.`" . $dbName . "` = " . $result[$prefsName];
        break;
    }
    if(isset($result[$prefsName . "_missing"]) && $result[$prefsName . "_missing"] == 1) {
      $query .= " OR `" . $table . "`.`" . $dbName . "` < 0";
    }
    return $query;
  }

  function filterRange($prefsName, $dbName, $query, $table="schools") {
    global $result;
    $query .= "((`" . $dbName . "_25` >= " . $result[$prefsName . "_min"] . " AND `" . $dbName . "_25` <= " . $result[$prefsName . "_max"] . ") OR (`" . $dbName . "_75` >= " . $result[$prefsName . "_min"] . " AND `" . $dbName . "_75` <= " . $result[$prefsName . "_max"] . ") OR (`" . $dbName . "_25` <= " . $result[$prefsName . "_min"] . " AND `" . $dbName . "_75` >= " . $result[$prefsName . "_max"] . "))";
    return $query;
  }

  $loc = locate($result["loc_dist_addr"]);
  $haver = "( 3959 * acos( cos( radians(" . $loc["lat"] . ") ) * cos( radians( `schools`.`latitude` ) ) * cos( radians( `schools`.`longitude` ) - radians(" . $loc["long"] . ") ) + sin( radians(" . $loc["lat"] . ") ) * sin( radians( `schools`.`latitude` ) ) ) ) AS `distance`";
  $satMin = "(`supplementary`.`sat_cr_25`+`supplementary`.`sat_mt_25`+`supplementary`.`sat_wr_25`) as `sat_25`";
  $satMax = "(`supplementary`.`sat_cr_75`+`supplementary`.`sat_mt_75`+`supplementary`.`sat_wr_75`) as `sat_75`";
  $query = "SELECT DISTINCT `schools`.`id`,`schools`.`name`,`schools`.`city`,`schools`.`state`,`supplementary`.`sat_cr_25`,`supplementary`.`sat_cr_75`,`supplementary`.`sat_mt_25`,`supplementary`.`sat_mt_75`,`supplementary`.`sat_wr_25`,`supplementary`.`sat_wr_75`,`supplementary`.`act_cm_25`,`supplementary`.`act_cm_75`,`supplementary`.`applied`,`supplementary`.`admitted`," . $haver . "," . $satMin . "," . $satMax . " FROM `schools`,`supplementary`,`major_offerings` WHERE `schools`.`id` = `supplementary`.`id` AND `schools`.`id` = `major_offerings`.`school_id` AND ";
  
  $query .= "(";

  switch($result["loc_type"]) {
    case 'none':
    case 'distance': // implemented later (needs to use HAVING instead of WHERE)
    default:
      // constrain nothing
      $query .= "TRUE";
      break;
    case 'setting':
      foreach(explode(",", $result["loc_setting"]) as $item) {
        $query .= "`schools`.`urbanization` = " . $item . " OR "; // TODO: watch for SQL injection
      }
      $query = substr($query, 0, strlen($query) - 4);
      break;
    case 'state':
      foreach(explode(",", $result["loc_state"]) as $item) {
        $query .= "`schools`.`state` = '" . $item . "' OR ";
      }
      $query = substr($query, 0, strlen($query) - 4);
      break;
  }

  $query .= ") AND (";

  $query = filterBasic("level", "level", $query);

  $query .= ") AND (";

  $query = filterBasic("control", "control", $query);

  $query .= ") AND (";

  $query = filterBasic("degrees", "max_degree", $query);

  $query .= ") AND (";

  $query = filterBasic("majors", "major_id", $query, "major_offerings");

  $query .= ") AND (";

  $query = filterOption("black", "historically_black", $query);
  $query .= ") AND (";
  $query = filterOption2("hospital", "has_hospital", $query);
  $query .= ") AND (";
  $query = filterOption2("med_deg", "grants_med_deg", $query);
  $query .= ") AND (";
  $query = filterOption("tribal", "tribal", $query);
  $query .= ") AND (";
  $query = filterOption("public", "open_to_public", $query);

  $query .= ") AND (";

  // TODO: prevent SQL injections
  $query = filterRange("sat_cr", "sat_cr", $query);
  $query .= ") AND (";
  $query = filterRange("sat_mt", "sat_mt", $query);
  $query .= ") AND (";
  $query = filterRange("sat_wr", "sat_wr", $query);
  $query .= ") AND (";
  $query = filterRange("act", "act_cm", $query);
  $query .= ") AND (";
  $query = filterRange("act_en", "act_en", $query);
  $query .= ") AND (";
  $query = filterRange("act_mt", "act_mt", $query);
  $query .= ") AND (";
  $query = filterRange("act_wr", "act_wr", $query);

  $query .= ") AND (";

  $query .= "(`admitted`/`applied`) >= " . ($result["accept_min"]/100.0) . " AND (`admitted`/`applied`) <= " . ($result["accept_max"]/100.0);

  $query .= ") AND (";

  $query .= "(`enroll_m`/`enroll`) >= " . ($result["male_min"]/100.0) . " AND (`enroll_m`/`enroll`) <= " . ($result["male_max"]/100.0);

  $query .= ") AND (";

  $query = filterOption2("housing", "campus_housing", $query, "supplementary");
  $query .= ") AND (";
  $query = filterOption2("board", "board_provided", $query, "supplementary");
  $query .= ") AND (";
  $query = filterOption2("campus_required", "campus_required", $query, "supplementary");

  $query .= ") AND (";

  $subquery = "";
  foreach(explode(",", $result["dist"]) as $item) {
    $col = NULL;
    $cond = " = 1";
    switch($item) {
      case "all":
        $col = "all_dist";
        break;
      case "undergrad":
        $col = "under_dist";
        break;
      case "graduate":
        $col = "grad_dist";
        break;
      case "none":
        $col = "no_dist";
        break;
      case "missing":
        $col = "no_dist";
        $cond = "< 0";
      default:
        continue;
    }
    $subquery .= "`supplementary`.`" . $col . "` " . $cond . " OR ";
  }
  $query .= substr($subquery, 0, strlen($subquery) - 4);

  $query .= ") HAVING ";

  if($result["loc_type"] == 'distance') {
    $query .= " `distance` >= " . $result["loc_dist_min"] . " AND `distance` <= " . $result["loc_dist_max"] . " AND ";
  }

  $query = filterRange("sat", "sat", $query);

  $query .= " LIMIT 0,101";


  //echo $query . "<br />";
  $stmt = $mysql->prepare($query);
  $stmt->execute();
  $schools = getResult($stmt);
  $stmt->close();
  $count = count($schools);
?>

    <div class="container">

      <div class="starter-template">
        <h1>View selected schools</h1>

        <?php
          if($count > 0 && $count <= 100) {
            echo '<div class="result-count">' . $count . ' results found.</div>';
          }
        ?>
        <table class="results">
          <?php if($count > 100): ?>
            <tr>
              <td>More than 100 results were found. Please narrow your search.</td>
            </tr>
          <?php elseif($count > 0): ?>
            <tr>
              <th>Name</th>
              <th>City</th>
              <th>State</th>
              <th>SAT Range</th>
              <th>ACT Range</th>
              <th>Acceptance</th>
              <th>List</th>
            </tr>
            <?php
              foreach($schools as $school) {
                echo formatSchool($school);
              }
            ?>
          <?php else: ?>
            <tr>
              <td>No results found.</td>
            </tr>
          <?php endif; ?>
        </table>
      </div>

    </div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="js/jquery.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <script src="js/schools.js"></script>
  </body>
</html>