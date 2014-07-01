<?php
  require_once "util/util.php";

  function g($name) {
    if(isset($_POST[$name])) {
      return $_POST[$name];
    } else {
      return "";
    }
  }

  function f($name) {
    $a = g($name);
    if(is_numeric($a)) {
      $f = floatval($a);
      return $f < 0 ? 0 : $f;
    } else {
      return -1.0;
    }
  }

  function i($name) {
    $a = g($name);
    if(preg_match('/^\d+$/',$a)) {
      $i = intval($a);
      return $i < 0 ? 0 : $i;
    } else {
      return -1;
    }
  }

  $codes = array(
    1 => "You must enter a valid GPA.",
    2 => "You must enter a valid SAT or ACT score.",
    3 => "Internal error. Please reload and try again.",
    4 => "The minimum size cannot be greater than the maximum.",
    5 => "The minimum cost cannot be greater than the maximum.",
  );

  function a($code) {
    global $error, $codes;
    assert(isset($error));
    if(in_array($code, $error)) {
      echo $codes[$code];
    }
  }

  require_once "util/get-db.php";

  $error = array();

  if(isset($_POST['size-type'], $_POST['loc-type']) && anySet("gpa", "sat", "act", "majors", "size-min-simp", "size-max-simp", "size-min-adv", "size-max-adv", "dist-min", "dist-max", "lat", "long", "cost-min", "cost-max")) {

    $gpa = f("gpa");

    if($gpa < 0) {
      // no gpa
      array_push($error, 1);
    } else if($gpa > 4.0) {
      $gpa = 4.0;
    }

    $sat = i("sat");
    $act = i("act");

    if($sat < 0 && $act < 0) {
      // no sat or act
      array_push($error, 2);
    } else {
      if($sat > 2400) $sat = 2400;
      if($sat < 600) $sat = 600;
      if($act > 36) $act = 36;
    }

    $majors = preg_split("/,/", g("majors"));
    //if(count($majors) <= 0) $majors = array(0);

    $sizeMin = 0;
    $sizeMax = 25000;

    $sizeType = $_POST['size-type'];
    $validSizes = false;
    if($sizeType != "simple" && $sizeType != "advanced") {
      // no sizeType set
      array_push($error, 3); // "internal error, try again"
    } else if($sizeType == "simple") {
      $sizeMin = i("size-min-simp");
      $sizeMax = i("size-max-simp");
      $validSizes = true;
    } else {
      assert($sizeType == "advanced");
      $sizeMin = i("size-min-adv");
      $sizeMax = i("size-max-adv");
      $validSizes = true;
    }
    if($validSizes) {
      if($sizeMin < 0) $sizeMin = 0;
      if($sizeMax < 0) $sizeMax = 25000;
      if($sizeMax < $sizeMin) {
        // school size min is greater than max
        array_push($error, 4);
      }
    }

    // TODO location (zip/lat-long) stuff

    $costMin = i("cost-min");
    $costMax = i("cost-max");
    if($costMin < 0) $costMin = 0;
    if($costMax < 0) $costMax = 75000;
    if($costMax < $costMin) {
      // cost min is greater than max
      array_push($error, 5);
    }

    if(count($error) <= 0) {

      // accept input
      require_once "util/results.php";
      die();

    }

  }

  $page = 1;
  $extra = '<link href="styles/details.css" rel="stylesheet" />';
  require_once "util/header.php";
?>

    <div class="container">

      <div class="starter-template">
        <h1>How it works</h1>
        <p class="start">Just give us what information you can, and we'll do the rest.</p>

        <p class="error"><?php a(3); ?></p>

        <form class="lead" method="post" action="#" id="form">
          <div class="popup gpa-popup">
            <div class="gpa-popup-container">
              <table>
                <tr>
                  <th>Class Name (Optional)</th>
                  <th width="92px">Semesters</th>
                  <th width="65px">Grade</th>
                </tr>
              </table>
              <table class="gpa-class-container">

              </table>
              <button type="button" class="btn btn-success" onclick="addGPARow()">Add class</button>
              <button type="button" class="btn btn-success" onclick="calcGPA()">Calculate</button>
              <button type="button" class="btn btn-success" onclick="toggleGPA()">Cancel</button>
            </div>
          </div>

          <ol>
            <li>Enter your GPA and any standardized test scores.</li>
              <div class="data-container scores">
                <table>
                  <tr>
                    <td class="error"><?php a(1); ?></td>
                    <td class="lbl-td"><label for="gpa">GPA:</label></td>
                    <td class="input-td"><input type="text" placeholder="4.0" class="form-control" id="gpa" name="gpa"<?php e("gpa"); ?> /></td>
                    <td class="extra-td"><a class="small" onclick="toggleGPA()">Calculate</a></td>
                  </tr>
                  <tr>
                    <td class="error" rowspan="2"><?php a(2); ?></td>
                    <td class="lbl-td"><label for="sat">SAT:</label></td>
                    <td class="input-td"><input type="text" placeholder="2400" class="form-control" id="sat" name="sat"<?php e("sat"); ?> /></td>
                    <td class="extra-td">&nbsp;</td>
                  </tr>
                  <tr>
                    <td class="lbl-td"><label for="act">ACT:</label></td>
                    <td class="input-td"><input type="text" placeholder="36" class="form-control" id="act" name="act"<?php e("act"); ?> /></td>
                    <td class="extra-td">&nbsp;</td>
                  </tr>
                </table>
              </div>

              <li>Indicate your desired major(s).</li>
              <div class="data-container major">
                <div class="major-left">
                  <?php

                    $stmt = $mysql->prepare("SELECT `id`,`name` FROM `major_sections`");
                    $stmt->execute();
                    $stmt->store_result();

                    $secID = NULL;
                    $secName = NULL;
                    $stmt->bind_result($secID, $secName);

                    while($stmt->fetch()) {

                      echo '<div class="major-section major-hidden major-sec-' . $secID . '">';
                      echo '<a class="indicator indicator-' . $secID . '" onclick="toggleSec(' . $secID . ')">[+]</a>';
                      echo $secName;
                      echo '<a class="add" onclick="addAllMajors(' . $secID . ')">Add All</a>';
                      echo '<ul>';

                      $stmt2 = $mysql->prepare("SELECT `id`,`name` FROM `majors` WHERE `section` = ?");
                      $stmt2->bind_param("i", $secID);
                      $stmt2->execute();
                      $stmt2->store_result();

                      $majorID = NULL;
                      $majorName = NULL;
                      $stmt2->bind_result($majorID, $majorName);

                      while($stmt2->fetch()) {
                        echo '<li class="major-' . $majorID . '">' . $majorName . '<a class="add" onclick="addMajor(' . $majorID . ',\'' . $majorName . '\')">Add</a></li>';
                      }

                      $stmt2->close();

                      echo '</ul>';
                      echo '</div>';

                    }

                    $stmt->close();

                  ?>
                </div>
                <div class="major-right">
                  <ul>
                    <?php

                      if(isset($_POST['majors'])) {
                        $split = preg_split("/,/", $_POST['majors']);
                        foreach($split as $item) {
                          if(strlen($item) <= 0) continue;
                          $stmt = $mysql->prepare("SELECT `name` FROM `majors` WHERE `id` = ?");
                          $stmt->bind_param("i", $item);
                          $stmt->execute();
                          $name = NULL;
                          $stmt->bind_result($name);
                          $stmt->fetch();
                          echo '<li class="major-' . $item . '">' . $name . '<a class="remove" onclick="removeMajor(' . $item . ')">Remove</a></li>';
                          $stmt->close();
                        }
                      }

                    ?>
                  </ul>
                  <input type="hidden" id="majors" name="majors" value="" />
                </div>
              </div>

              <li>Indicate any size preferences you may have.</li>
              <table class="data-container size-prefs">
                <tr>
                  <td class="error"><?php a(4); ?></td>
                  <td class="size-opt">
                    <select class="form-control simple-size" name="size-min-simp" id="size-min-simp">
                      <option selected>Tiny (2000 students or less)</option>
                      <option>Small (2000 - 5000 students)</option>
                      <option>Medium (5000 - 15000 students)</option>
                      <option>Large (15000 students or more)</option>
                    </select>
                    <input type="text" placeholder="0" class="form-control adv-size" name="size-min-adv" id="size-min-adv" />
                  </td>
                  <td class="to"><span id="to">to</span></td>
                  <td class="size-opt">
                    <select class="form-control simple-size" name="size-max-simp" id="size-max-simp">
                      <option>Tiny (2000 students or less)</option>
                      <option>Small (2000 - 5000 students)</option>
                      <option>Medium (5000 - 15000 students)</option>
                      <option selected>Large (15000 students or more)</option>
                    </select>
                    <input type="text" placeholder="100000" class="form-control adv-size" name="size-max-adv" id="size-max-adv" />
                  </td>
                  <td class="adv-btn"><a id="advanced-size" class="small" onclick="toggleAdvancedSize()">Advanced</a></td>
                </tr>
              </table>

              <li>Indicate any geographical preferences you may have.</li>
              <table class="data-container sentence loc-prefs">
                <tr>
                  <td class="error"><?php a(5); ?></td>
                  <td class="mid-sent">
                    Between <input type="text" placeholder="0" class="form-control" name="dist-min" id="dist-min"<?php e("dist-min"); ?> /> and 
                    <input type="text" placeholder="25000" class="form-control" name="dist-max" id="dist-max"<?php e("dist-max"); ?> /> miles away from 
                    <input type="text" placeholder="Zip" class="form-control simple-loc" name="zip" id="zip"<?php e("zip"); ?> />
                    <input type="text" placeholder="Lat" class="form-control adv-loc" name="lat" id="lat"<?php e("lat"); ?> />
                    <input type="text" placeholder="Long" class="form-control adv-loc" name="long" id="long"<?php e("long"); ?> />
                  </td>
                  <td class="adv-btn"><a id="advanced-loc" class="small" onclick="toggleAdvancedLoc()">Advanced</a></td>
                </tr>
              </table>

              <li>Indicate how much tuition you can afford.</li>
              <div class="data-container sentence">
                Between <input type="text" placeholder="0" class="form-control" name="cost-min" id="cost-min"<?php e("cost-min"); ?> /> and <input type="text" placeholder="75000" class="form-control" name="cost-max" id="cost-max"<?php e("cost-max"); ?> /> dollars per year.
              </div>

              <input type="hidden" name="size-type" id="size-type" value="simple" />
              <input type="hidden" name="loc-type" id="loc-type" value="simple" />

              <li><input type="button" class="btn btn-primary" value="See your results" id="results" /></li>
          </ol>
        </form>
      </div>

    </div><!-- /.container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>-->
    <script src="js/jquery.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <script src="js/details.js"></script>
  </body>
</html>
