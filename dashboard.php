<?php
  $page = 0;
  $extra = '<link href="styles/dash.css" rel="stylesheet" />';
  require_once "util/header-signedin.php";

  function checkBox($value, $title, $name) {
    $val = valOf($name);
    echo '<label><input type="checkbox" name="' . $name . '[]" value="' . $value . '"' . (($val != NULL && in_array($value, $val)) ? ' checked' : '') . ' />' . $title . '</label>';
  }

  function checkBoxArr($arr, $name) {
    foreach($arr as $key => $val) {
      checkBox($key, $val, $name);
    }
  }

  function setting($value, $title) {
    checkBox($value, $title, 'loc-setting');
  }

  function settingArr($arr) {
    foreach($arr as $key => $val) {
      setting($key, $val);
    }
  }

  function state($value, $title) {
    checkBox($value, $title, 'loc-state');
  }

  function stateArr($arr) {
    foreach($arr as $key => $val) {
      state($key, $val);
    }
  }

  function score($name, $label, $min, $max) {
    echo '<tr>';
    echo '<td><label for="' . $name . '-min">' . $label . ' Between:</label></td>';
    echo '<td><input type="text" name="' . $name . '-min" id="' . $name . '-min" class="form-control" placeholder="' . $min . '" /> and <input type="text" name="' . $name . '-max" id="' . $name . '-max" class="form-control" placeholder="' . $max . '" /></td>';
    echo '</tr>';
  }

  function scoreACT($name, $label) {
    score($name, $label, 1, 36);
  }

  $changes = false;
  $error = 0;
  if(anySet('loc-type', 'level-type', 'control-type', 'degrees-type', 'black', 'hospital', 'med-deg', 'tribal', 'public', 'sat', 'act')) {
    $locType = b('loc-type');
    if(!in_array($locType, array('none', 'setting', 'distance', 'state'))) {
      $error = 1; // kids messin' with my code
    } else {
      $stmt = $mysql->prepare("UPDATE `prefs` SET `loc_type` = ?, `loc_setting` = ?, `loc_dist_min` = ?, `loc_dist_max` = ?, `loc_dist_addr` = ?, `loc_state` = ?, `level_type` = ?, `level` = ?");
      $stmt->bind_param("ssiissss", $locType, implode(',', bArr('loc-setting')), bInt('loc-dist-min'), bInt('loc-dist-max'), b('loc-dist-from'), implode(',', bArr('loc-state')), b('level-type'), implode(',', bArr('level')));
      $stmt->execute();
      $stmt->close();
    }
  }

  $stmt = $mysql->prepare("SELECT `id` FROM `prefs` WHERE `id` = ?");
  $stmt->bind_param("i", $id);
  $stmt->execute();

  if(!$stmt->fetch()) {
    $stmt2 = $mysql->prepare("INSERT INTO `prefs` (`id`) VALUES(?)");
    $stmt2->bind_param("i", $id);
    $stmt2->execute();
    $stmt2->close();
  }
  $stmt->close();

  $stmt = $mysql->prepare("SELECT `loc_type`,`loc_setting`,`loc_dist_min`,`loc_dist_max`,`loc_dist_addr`,`loc_state`,`level_type`,`level` FROM `prefs` WHERE `id` = ?");
  $stmt->bind_param("i", $id);
  $stmt->execute();

  $vars = array(
      "loc-type" => NULL,
      "loc-setting" => NULL,
      "loc-dist-min" => NULL,
      "loc-dist-max" => NULL,
      "loc-dist-from" => NULL,
      "loc-state" => NULL,
      "level-type" => NULL,
      "level" => NULL,
    );
  $stmt->bind_result($vars["loc-type"], $vars["loc-setting"], $vars["loc-dist-min"], $vars["loc-dist-max"], $vars["loc-dist-from"], $vars["loc-state"], $vars["level-type"], $vars["level"]);
  assert($stmt->fetch());
  $stmt->close();
  $vars["loc-setting"] = explode(",", $vars["loc-setting"]);
  $vars["loc-state"] = explode(",", $vars["loc-state"]);
  $vars["level"] = explode(",", $vars["level"]);

  function valOf($name) {
    global $vars;
    if(isset($_POST[$name])) {
      return $_POST[$name];
    } else if(isset($vars[$name])) {
      return $vars[$name];
    } else {
      return NULL;
    }
  }

  function checked($name, $var) {
    global $vars;
    if($vars[$var] == $name) {
      echo " checked";
    }
  }

  function loc($name) {
    checked($name, 'loc-type');
  }

  function lvl($name) {
    checked($name, 'level-type');
  }

  function e0($name) {
    $val = valOf($name);
    if($val != NULL) {
      echo ' value="' . $val . '"';
    }
  }
?>

    <div class="container">

      <div class="starter-template">
        <h1>Change your preferences</h1>

        <form action="#" method="post" class="dash-form">
          <h2>Location</h2>
          <label><input type="radio" name="loc-type" value="none" onclick="updateLocation()"<?php loc("none"); ?> />No Preference</label>
          <div id="loc-type-none" class="loc">
            Include schools from the entire United States.
          </div>
          <label><input type="radio" name="loc-type" value="setting" onclick="updateLocation()"<?php loc("setting"); ?> />Setting</label>
          <table id="loc-type-setting" class="loc">
            <tr>
              <td>
                <?php
                  settingArr(array("11" => "Large City", "12" => "Midsize City", "13" => "Small City", "21" => "Large Suburb", "22" => "Midsize Suburb", "23" => "Small Suburb"));
                ?>
              </td>
              <td>
                <?php
                  settingArr(array("31" => "Fringe Town", "32" => "Distance Town", "33" => "Remote Town", "41" => "Fringe Rural", "42" => "Distant Rural", "43" => "Remote Rural"));
                ?>
              </td>
            </tr>
            <tr>
              <td colspan="2">
                <label><input type="checkbox" name="loc-setting[]" value="-3" />Include Schools with Unknown Settings</label>
              </td>
            </tr>
          </table>
          <label><input type="radio" name="loc-type" value="distance" onclick="updateLocation()"<?php loc("distance"); ?> />Distance</label>
          <div id="loc-type-distance" class="loc">
            Between <input type="text" name="loc-dist-min" placeholder="0" class="form-control"<?php e0("loc-dist-min"); ?> /> and <input type="text" name="loc-dist-max" placeholder="25000" class="form-control"<?php e0('loc-dist-max'); ?> /> miles away from <input type="text" name="loc-dist-from" placeholder="Address/Zip Code" class="form-control addr"<?php e0('loc-dist-from'); ?> />
          </div>
          <label><input type="radio" name="loc-type" value="state" onclick="updateLocation()"<?php loc("state"); ?> />State</label>
          <table class="sectioned loc" id="loc-type-state">
            <tr>
              <td colspan="2"><h3>New England</h3></td>
            </tr>
            <tr>
              <td>
                <?php
                  stateArr(array("CT" => "Connecticut", "ME" => "Maine", "MA" => "Massachusetts"));
                ?>
              </td>
              <td>
                <?php
                  stateArr(array("NH" => "New Hampshire", "RI" => "Rhode Island", "VT" => "Vermont"));
                ?>
              </td>
            </tr>
            <tr>
              <td colspan="2"><h3>Mid-Atlantic</h3></td>
            </tr>
            <tr>
              <td>
                <?php
                  stateArr(array("NJ" => "New Jersey", "NY" => "New York"));
                ?>
              </td>
              <td>
                <?php
                  state("PA", "Pennsylvania");
                ?>
              </td>
            </tr>
            <tr>
              <td colspan="2"><h3>East North Central</h3></td>
            </tr>
            <tr>
              <td>
                <?php
                  stateArr(array("IL" => "Illinois", "IN" => "Indiana", "MI" => "Michigan"));
                ?>
              </td>
              <td>
                <?php
                  stateArr(array("OH" => "Ohio", "WI" => "Wisconsin"));
                ?>
            </tr>
            <tr>
              <td colspan="2"><h3>West North Central</h3></td>
            </tr>
            <tr>
              <td>
                <?php
                  stateArr(array("IA" => "Iowa", "KS" => "Kansas", "MN" => "Minnesota", "MO" => "Missouri"));
                ?>
              </td>
              <td>
                <?php
                  stateArr(array("NE" => "Nebraska", "ND" => "North Dakota", "SD" => "South Dakota"));
                ?>
              </td>
            </tr>
            <tr>
              <td colspan="2"><h3>South Atlantic</h3></td>
            </tr>
            <tr>
              <td>
                <?php
                  stateArr(array("DE" => "Delaware", "FL" => "Florida", "GA" => "Georgia", "MD" => "Maryland", "NC" => "North Carolina"));
                ?>
              </td>
              <td>
                <?php
                  stateArr(array("SC" => "South Carolina", "VA" => "Virginia", "DC" => "Washington, D.C.", "WV" => "West Virginia"));
                ?>
              </td>
            </tr>
            <tr>
              <td colspan="2"><h3>East South Central</h3></td>
            </tr>
            <tr>
              <td>
                <?php
                  stateArr(array("AL" => "Alabama", "KY" => "Kentucky"));
                ?>
              </td>
              <td>
                <?php
                  stateArr(array("MS" => "Mississippi", "TN" => "Tennessee"));
                ?>
              </td>
            </tr>
            <tr>
              <td colspan="2"><h3>West South Central</h3></td>
            </tr>
            <tr>
              <td>
                <?php
                  stateArr(array("AR" => "Arkansas", "LA" => "Louisiana"));
                ?>
              </td>
              <td>
                <?php
                  stateArr(array("OK" => "Oklahoma", "TX" => "Texas"));
                ?>
              </td>
            </tr>
            <tr>
              <td colspan="2"><h3>Mountain</h3></td>
            </tr>
            <tr>
              <td>
                <?php
                  stateArr(array("AZ" => "Arizona", "CO" => "Colorado", "ID" => "Idaho", "MT" => "Montana"));
                ?>
              </td>
              <td>
                <?php
                  stateArr(array("NV" => "Nevada", "NM" => "New Mexico", "UT" => "Utah", "WY" => "Wyoming"));
                ?>
              </td>
            </tr>
            <tr>
              <td colspan="2"><h3>Pacific</h3></td>
            </tr>
            <tr>
              <td>
                <?php
                  stateArr(array("AK" => "Alaska", "CA" => "California", "HI" => "Hawaii"));
                ?>
              </td>
              <td>
                <?php
                  stateArr(array("OR" => "Oregon", "WA" => "Washington"));
                ?>
              </td>
            </tr>
            <tr>
              <td colspan="2"><h3>Other</h3></td>
            </tr>
            <tr>
              <td>
                <?php
                  stateArr(array("AS" => "American Samoa", "FM" => "Federated States of Micronesia", "GU" => "Guam", "MH" => "Marshall Islands"));
                ?>
              </td>
              <td>
                <?php
                  stateArr(array("MP" => "Northern Mariana Islands", "PW" => "Palau", "PR" => "Puerto Rico", "VI" => "Virgin Islands"));
                ?>
              </td>
            </tr>
          </table>

          <h2>Level</h2>
          <label><input type="radio" name="level-type" value="none" onclick="updateLevel()"<?php lvl('none'); ?> />No Preference</label>
          <div class="loc" id="level-type-none">Include schools of all levels.</div>
          <label><input type="radio" name="level-type" value="some" onclick="updateLevel()"<?php lvl('some'); ?> />Preference</label>
          <table class="loc" id="level-type-some">
            <tr>
              <td>
                <?php
                  checkBoxArr(array("1" => "4+ Years", "2" => "At least 2 but less than 4 years"), 'level');
                ?>
              </td>
              <td>
                <?php
                  checkBoxArr(array("3" => "Less than 2 years", "-3" => "Include Schools with Unknown Level"), 'level');
                ?>
              </td>
            </tr>
          </table>

          <h2>Control</h2>
          <label><input type="radio" name="control-type" value="none" onclick="updateControl()" />No Preference</label>
          <div class="loc" id="control-type-none">Include all public and private schools.</div>
          <label><input type="radio" name="control-type" value="some" onclick="updateControl()" />Preference</label>
          <table class="loc" id="control-type-some">
            <tr>
              <td>
                <?php
                  checkBoxArr(array("2" => "Private, non-profit", "3" => "Private, for-profit"), 'control');
                ?>
              </td>
              <td>
                <?php
                  checkBoxArr(array("1" => "Public", "-3" => "Include Schools with Unknown Control"), 'control');
                ?>
              </td>
            </tr>
          </table>

          <h2>Degree</h2>
          <label><input type="radio" name="degrees-type" value="none" onclick="updateDegrees()" />No Preference</label>
          <div class="loc" id="degrees-type-none">Include schools that grant all degrees and those that don't grant any.</div>
          <label><input type="radio" name="degrees-type" value="some" onclick="updateDegrees()" />Preference</label>
          <table class="loc" id="degrees-type-some">
            <tr>
              <td>
                <?php
                  checkBoxArr(array("11" => "Doctor's - research/scholarship and professional practice", "12" => "Doctor's - research/scholarship", "13" => "Doctor's - professional practice", "14" => "Doctor's - other"), 'degrees');
                ?>
              </td>
              <td>
                <?php
                  checkBoxArr(array("20" => "Master's", "30" => "Bachelor's", "40" => "Associate's", "0" => "Does not grant degrees"), 'degrees');
                ?>
              </td>
            </tr>
            <tr>
              <td colspan="2">
                <?php
                  checkBox('-3', 'Include Schools with Unknown Degree Granting Status', 'degrees');
                ?>
              </td>
            </tr>
          </table>

          <h2>History and Facts</h2>
          <table>
            <tr>
              <td><label for="black">Historically Black:</label></td>
              <td>
                <select name="black" id="black" class="form-control">
                  <option value="-1">No Preference</option>
                  <option value="1">Yes</option>
                  <option value="0">No</option>
                </select>
              </td>
            </tr>
            <tr>
              <td><label for="hospital">Has Hospital:</label></td>
              <td>
                <select name="hospital" id="hospital" class="form-control">
                  <option value="0">No Preference</option>
                  <option value="1">Yes</option>
                  <option value="2">No</option>
                </select>
              </td>
            </tr>
            <tr>
              <td colspan="2"><label><input type="checkbox" name="hospital-missing" value="yes" />Include Schools with Unknown Hospital Presence</label></td>
            </tr>
            <tr>
              <td><label for="med-deg">Grants Medical Degree:</label></td>
              <td>
                <select name="med-deg" id="med-deg" class="form-control">
                  <option value="0">No Preference</option>
                  <option value="1">Yes</option>
                  <option value="2">No</option>
                </select>
              </td>
            </tr>
            <tr>
              <td colspan="2"><label><input type="checkbox" name="med-deg-missing" value="yes" />Inculde Schools with Unknown Medical Degree Grating Status</label></td>
            </tr>
            <tr>
              <td><label for="tribal">Tribal College or University:</label></td>
              <td>
                <select name="tribal" id="tribal" class="form-control">
                  <option value="-1">No Preference</option>
                  <option value="1">Yes</option>
                  <option value="0">No</option>
                </select>
              </td>
            </tr>
            <tr>
              <td><label for="public">Open to the General Public:</label></td>
              <td>
                <select name="public" id="public" class="form-control">
                  <option value="-1">No Preference</option>
                  <option value="1">Yes</option>
                  <option value="0">No</option>
                </select>
              </td>
            </tr>
          </table>

          <h2>Scores</h2>
          <table class="scores">
            <?php
              score("sat", "SAT", 600, 2400);
              score("sat-mt", "SAT Math", 200, 800);
              score("sat-cr", "SAT Reading", 200, 800);
              score("sat-wr", "SAT Writing", 200, 800);
              scoreACT("act", "ACT");
              scoreACT("act-en", "ACT English");
              scoreACT("act-mt", "ACT Math");
              scoreACT("act-wr", "ACT Writing");
            ?>
          </table>

          <h2>Percentages</h2>
          <table class="scores">
            <?php
              score("accept", "Acceptance Rate", 0, 100);
              score("males", "Percent Male", 0, 100);
            ?>
          </table>

          <h2>Campus Life</h2>
          <table>
            <tr>
              <td><label for="housing">On-Campus Housing Provided:</label></td>
              <td>
                <select name="housing" id="housing" class="form-control">
                  <option value="0">No Preference</option>
                  <option value="1">Yes</option>
                  <option value="2">No</option>
                </select>
              </td>
            </tr>
            <tr>
              <td colspan="2"><label><input type="checkbox" name="housing-missing" value="yes" />Include Schools with Unknown Housing Status</label></td>
            </tr>
            <tr>
              <td><label for="board">Meal Plan Available:</label></td>
              <td>
                <select name="board" id="board" class="form-control">
                  <option value="0">No Preference</option>
                  <option value="1">Yes</option>
                  <option value="3">No</option>
                </select>
              </td>
            </tr>
            <tr>
              <td colspan="2"><label><input type="checkbox" name="board-missing" value="yes" />Include Schools with Unknown Meal Plan Status</label></td>
            </tr>
            <tr>
              <td><label for="campus-required">Required to Live on Campus:</label></td>
              <td>
                <select name="campus-required" id="campus-required" class="form-control">
                  <option value="0">No Preference</option>
                  <option value="1">Yes</option>
                  <option value="2">No</option>
                </select>
              </td>
            </tr>
            <tr>
              <td colspan="2"><label><input type="checkbox" name="campus-required-missing" value="yes" />Include Schools with Unknown Requirement</label></td>
            </tr>
          </table>

          <h2>Distance Learning</h2>
          <table>
            <tr>
              <td>
                <?php
                  checkBoxArr(array("all" => "All programs offered", "undergrad" => "Undergraduate programs offered"), 'dist');
                ?>
              </td>
              <td>
                <?php
                  checkBoxArr(array("graduate" => "Graduate programs offered", "none" => "No programs offered"), 'dist');
                ?>
              </td>
            </tr>
            <tr>
              <td colspan="2"><label><input type="checkbox" name="dist-missing" value="yes" />Include Schools with Unknown Distance Status</label></td>
            </tr>
          </table>

          <input type="submit" class="btn btn-primary" value="Save Changes" />
        </form>
      </div>

    </div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <script src="js/dashboard.js"></script>
  </body>
</html>