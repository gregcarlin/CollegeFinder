<?php
  $page = 2;
  require_once "util/header-signedin.php";

  /*$cols = array("loc_type", "loc_setting", "loc_dist_min", "loc_dist_max", "loc_dist_addr", "loc_state", 
                "level_type", "level", "control_type", "control", "degrees_type", "degrees", 
                "majors_type", "majors", "black", "hospital", "hospital_missing", "med_deg", "med_deg_missing",
                "tribal", "public", "sat_min", "sat_max", "sat_mt_min", "sat_mt_max", "sat_cr_min", "sat_cr_max",
                "sat_wr_min", "sat_wr_max", "act_min", "act_max", "act_en_min", "act_en_max", "act_mt_min", "act_mt_max",
                "act_wr_min", "act_wr_max", "accept_min", "accept_max", "male_min", "male_max", "housing", "housing_missing",
                "board", "board_missing", "campus_required", "campus_required_missing", "dist"
               );
  $query = "SELECT ";
  foreach($cols as $item) { // hate this syntax
    $query .= '`' . $item . '`, ';
  }
  $query = substr($query, 0, strlen($query) - 2);
  $query .= " FROM `prefs` WHERE `id` = ?";

  $stmt = $mysql->prepare($query);*/
  $stmt = $mysql->prepare("SELECT * FROM `prefs` WHERE `id` = ?");
  $stmt->bind_param("i", $id);
  $stmt->execute();
  $result = $stmt->get_result()->fetch_assoc();
  $stmt->close();

  $query = "SELECT `schools`.`id`,`schools`.`name`,`schools`.`address`,`schools`.`city`,`schools`.`state`,`schools`.`website` FROM `schools`,`supplementary` WHERE `schools`.`id` = `supplementary`.`id` AND ";
  
  $query .= "(";

  switch($result["loc_type"]) {
    case 'none':
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
    case 'distance':
      // TODO
      break;
    case 'state':
      foreach(explode(",", $result["loc_state"]) as $item) {
        $query .= "`schools`.`state` = '" . $item . "' OR ";
      }
      $query = substr($query, 0, strlen($query) - 4);
      break;
  }

  $query .= ") AND (";

  switch($result["level_type"]) {
    case 'none':
    default:
      $query .= "TRUE";
      break;
    case 'some':
      foreach(explode(",", $result["level"]) as $item) {
        $query .= "`schools`.`level` = " . $item . " OR ";
      }
      $query = substr($query, 0, strlen($query) - 4);
      break;
  }

  $query .= ")";

  echo $query . "<br />";
  $stmt = $mysql->prepare($query);
  $stmt->execute();
  var_dump($stmt->get_result()->fetch_assoc());
?>

    <div class="container">

      <div class="starter-template">
        <h1>View selected schools</h1>

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