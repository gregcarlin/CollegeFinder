<?php
  $page = 2;
  require_once "util/header-signedin.php";

  $stmt = $mysql->prepare("SELECT * FROM `prefs` WHERE `id` = ?");
  $stmt->bind_param("i", $id);
  $stmt->execute();
  $result = $stmt->get_result()->fetch_assoc();
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

  $loc = locate($result["loc_dist_addr"]);
  $haver = "( 3959 * acos( cos( radians(" . $loc["lat"] . ") ) * cos( radians( `schools`.`latitude` ) ) * cos( radians( `schools`.`longitude` ) - radians(" . $loc["long"] . ") ) + sin( radians(" . $loc["lat"] . ") ) * sin( radians( `schools`.`latitude` ) ) ) ) AS `distance`";
  $query = "SELECT DISTINCT `schools`.`id`,`schools`.`name`,`schools`.`address`,`schools`.`city`,`schools`.`state`,`schools`.`website`,`schools`.`latitude`,`schools`.`longitude`," . $haver . " FROM `schools`,`supplementary`,`major_offerings` WHERE `schools`.`id` = `supplementary`.`id` AND `schools`.`id` = `major_offerings`.`school_id` AND ";
  
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

  $query .= ")";

  if($result["loc_type"] == 'distance') {
    $query .= " HAVING `distance` >= " . $result["loc_dist_min"] . " AND `distance` <= " . $result["loc_dist_max"];
  }

  echo $query . "<br />";
  $stmt = $mysql->prepare($query);
  $stmt->execute();

  $schools = array();
  $rs = $stmt->get_result();
  $school = $rs->fetch_assoc();
  while($school != NULL) {

    array_push($schools, $school);

    $school = $rs->fetch_assoc();

  }
?>

    <div class="container">

      <div class="starter-template">
        <h1>View selected schools</h1>

        <table>
          <?php
            //var_dump($schools);
            foreach($schools as $school) {
              echo '<tr>';
              echo '<td>' . $school["name"] . '</td>';
              //echo '<td>' . $school["address"] . '</td>';
              echo '<td>' . $school["city"] . '</td>';
              echo '<td>' . $school["state"] . '</td>';
              echo '</tr>';
            }
          ?>
        </table>
      </div>

    </div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="js/jquery.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <script src="js/dashboard.js"></script>
  </body>
</html>