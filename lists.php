<?php
  $page = 3;
  //$extra = '<link href="styles/dash.css" rel="stylesheet" />';
  require_once "util/header-signedin.php";

  $stmt = $mysql->prepare("SELECT * FROM `schools`,`lists`,`supplementary` WHERE `lists`.`student_id` = ? AND `lists`.`school_id` = `schools`.`id` AND `schools`.`id` = `supplementary`.`id`");
  $stmt->bind_param("i", $id);
  $stmt->execute();
  $result = getResult($stmt);
  $stmt->close();

  $lists = array_fill(0, 3, array());
  $listNames = array(0 => "Reach", 1 => "Target", 2 => "Safety");
  foreach($result as $school) {
    array_push($lists[$school['list_id']], $school);
  }
?>
    <div class="container">

      <div class="starter-template">

        <?php

          $len = count($lists);
          assert($len == count($listNames));
          for($i = 0; $i < $len; $i++) {
            echo '<h1>' . $listNames[$i] . '</h1>';
            if(count($lists[$i]) > 0) {
              echo '<table class="results" id="results-' . $i . '">';
              echo '<tr>';
              echo '<th>Name</th>';
              echo '<th>City</th>';
              echo '<th>State</th>';
              echo '<th>SAT Range</th>';
              echo '<th>ACT Range</th>';
              echo '<th>Acceptance</th>';
              echo '</tr>';
              foreach($lists[$i] as $school) {
                echo formatSchool($school);
              }
              echo '</table>';
            } else {
              echo 'You do not have any schools in this list! Add some by <a href="search.php">searching</a> for them.';
            }
          }

        ?>

      </div>

    </div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="js/jquery.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <script src="js/lists.js"></script>
  </body>
</html>