<?php
  $page = 3;
  $mode = 1;
  $title = 'Lists';
  $extra = '<link href="styles/lists.css" rel="stylesheet" />';
  require_once "util/header.php";

  $stmt = $mysql->prepare("SELECT * FROM `schools`,`lists`,`supplementary` WHERE `lists`.`student_id` = ? AND `lists`.`school_id` = `schools`.`id` AND `schools`.`id` = `supplementary`.`id` ORDER BY `lists`.`rank`");
  $stmt->bind_param("i", $id);
  $stmt->execute();
  $result = getSchools(getResult($stmt));
  $stmt->close();

  $lists = array_fill(0, 3, array());
  $listNames = array(0 => "Reach", 1 => "Target", 2 => "Safety");
  foreach($result as $school) {
    array_push($lists[$school->getOther('list_id')], $school);
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
              echo '<thead>';
              echo '<tr>';
              echo '<th>Name</th>';
              echo '<th>City</th>';
              echo '<th>State</th>';
              echo '<th>SAT Range</th>';
              echo '<th>ACT Range</th>';
              echo '<th>Acceptance</th>';
              echo '</tr>';
              echo '</thead>';
              echo '<tbody class="sortable">';
              foreach($lists[$i] as $school) {
                echo formatSchool($school, true);
              }
              echo '</tbody>';
              echo '</table>';
            } else {
              echo 'You do not have any schools in this list! Add some by <a href="search.php">searching</a> for them.';
            }
          }

        ?>

      </div>

    </div>
<?php
  $extraF = '<script src="js/jquery-ui.min.js"></script><script src="js/lists.js"></script>';
  require_once "util/footer.php";
?>