<?php

if(!isset($error)) {
  header("HTTP/1.0 404 Not Found");
  die();
}

assert(isset($gpa, $sat, $act, $majors, $sizeMin, $sizeMax, $sizeMin, $sizeMax, $costMin, $costMax));

$page = 1;
$extra = '<link href="styles/details.css" rel="stylesheet" />';
require_once "header.php";

?>

<div class="container">

  <div class="starter-template">

    <h1>We Recommend</h1>

    <div class="lead result">
      <?php

        require_once "util/get-db.php";

        $select = "SELECT `schools`.`name`,`schools`.`city`,`schools`.`state`,";
        $select .= "`supplementary`.`total_cost`,`supplementary`.`admitted`/`supplementary`.`applied`*100 AS `accept`,";
        $select .= "(`supplementary`.`sat_cr_25`+`supplementary`.`sat_mt_25`+`supplementary`.`sat_wr_25`+`supplementary`.`sat_cr_75`+`supplementary`.`sat_mt_75`+`supplementary`.`sat_wr_75`)/2 as `sat`,";
        $select .= "(`supplementary`.`act_cm_25`+`supplementary`.`act_cm_75`)/2 as `act` FROM `supplementary`,`schools` WHERE ";
        if(isset($sat)) {
          $stmt = $mysql->prepare($select . "`supplementary`.`sat_cr_25`+`supplementary`.`sat_mt_25`+`supplementary`.`sat_wr_25` < ? AND ? < `supplementary`.`sat_cr_75`+`supplementary`.`sat_mt_75`+`supplementary`.`sat_wr_75` AND `supplementary`.`id` = `schools`.`id` ORDER BY (`supplementary`.`admitted`/`supplementary`.`applied`)");
          $stmt->bind_param("ii", $sat, $sat);
        } else {
          assert(isset($act));
          $stmt = $mysql->prepare($select . "`supplementary`.`act_cm_25` < ? AND ? < `supplementary`.`act_cm_75` AND `supplementary`.`id` = `schools`.`id` ORDER BY (`supplementary`.`admitted`/`supplementary`.`applied`)");
          $stmt->bind_param("ii", $act, $act);
        }
        assert(isset($stmt));
        $stmt->execute();
        $stmt->store_result();
        $name = NULL;
        $city = NULL;
        $state = NULL;
        $cost = NULL;
        $accept = NULL;
        $sat = NULL;
        $act = NULL;
        $stmt->bind_result($name, $city, $state, $cost, $accept, $sat, $act);

        $categories = array("Reach", "Target", "Safety");
        $rows = $stmt->num_rows;
        $max = intval((1/3) * $rows);

        for($j=0; $j<count($categories); $j++) {
          echo '<table>
          <tr>
            <th colspan="8" class="header">' . $categories[$j] . '</th>
          </tr>
          <tr>
            <th>School</th>
            <th>City</th>
            <th>State</th>
            <th>GPA</th>
            <th>SAT</th>
            <th>ACT</th>
            <th>Acceptance</th>
            <th>Your Majors</th>
            <th>Undergraduates</th>
            <th>Graduates</th>
            <th>Tuition</th>
          </tr>';

          for($i=0; $i<$max; $i++) {
            $stmt->fetch();
            echo '<tr>';
            echo '<td>' . $name . '</td>';
            echo '<td>' . $city . '</td>';
            echo '<td>' . $state . '</td>';
            echo '<td>4.0</td>';
            echo '<td>' . round($sat) . '</td>';
            echo '<td>' . round($act) . '</td>';
            echo '<td>' . round($accept) . '%</td>';
            echo '<td>TODO</td>';
            echo '<td>1000</td>';
            echo '<td>2000</td>';
            echo '<td>' . ($cost == NULL ? "" : '$' . number_format($cost)) . '</td>';
            echo '</tr>';
          }
        }

        ?>
      </table>

      <div class="foot">
        Save these results and get more control. <a href="#">Sign up</a> for free.
      </div>

    </div>

  </div>

</div>