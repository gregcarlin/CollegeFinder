<?php
  $page = 2;
  $mode = 1; // TODO: remove need to log in
  $extra = '<link href="styles/search.css" rel="stylesheet" />';
  require_once "util/header.php";

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
    echo '<td><input type="text" name="' . $name . '-min" id="' . $name . '-min" class="form-control" placeholder="' . $min . '" value="' . valOf($name . "-min") . '" /> and <input type="text" name="' . $name . '-max" id="' . $name . '-max" class="form-control" placeholder="' . $max . '" value="' . valOf($name . "-max") . '" /></td>';
    echo '</tr>';
  }

  function scoreACT($name, $label) {
    score($name, $label, 1, 36);
  }

  $error = 0;

  if(isset($_POST['name']) && strlen($_POST['name']) > 0) {
    if(preg_match('/[^a-z_\-0-9 ]/i', $_POST['name'])) {
      $error = 2;
    } else {
      require_once "util/search-name.php";
      die();
    }
  }

  if(anySet('loc-type', 'level-type', 'control-type', 'degrees-type', 'black', 'hospital', 'med-deg', 'tribal', 'public', 'sat', 'act')) {
    $locType = b('loc-type');
    if(!in_array($locType, array('none', 'setting', 'distance', 'state'))) {
      $error = 1; // kids messin' with my code
    } else {
      $stmt = $mysql->prepare("UPDATE `prefs` SET `loc_type` = ?, `loc_setting` = ?, `loc_dist_min` = ?, `loc_dist_max` = ?, `loc_dist_addr` = ?, `loc_state` = ?, `level_type` = ?, `level` = ?, `control_type` = ?, `control` = ?, `degrees_type` = ?, `degrees` = ?, `majors_type` = ?, `majors` = ?, `black` = ?, `hospital` = ?, `hospital_missing` = ?, `med_deg` = ?, `med_deg_missing` = ?, `tribal` = ?, `public` = ?, `sat_min` = ?, `sat_max` = ?, `sat_mt_min` = ?, `sat_mt_max` = ?, `sat_cr_min` = ?, `sat_cr_max` = ?, `sat_wr_min` = ?, `sat_wr_max` = ?, `act_min` = ?, `act_max` = ?, `act_en_min` = ?, `act_en_max` = ?, `act_mt_min` = ?, `act_mt_max` = ?, `act_wr_min` = ?, `act_wr_max` = ?, `accept_min` = ?, `accept_max` = ?, `male_min` = ?, `male_max` = ?, `housing` = ?, `housing_missing` = ?, `board` = ?, `board_missing` = ?, `campus_required` = ?, `campus_required_missing` = ?, `dist` = ?");
      $stmt->bind_param("ssiissssssssssiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiis", $locType, implode(',', bArr('loc-setting')), bInt('loc-dist-min'), bInt('loc-dist-max'), b('loc-dist-from'), implode(',', bArr('loc-state')), b('level-type'), implode(',', bArr('level')), b('control-type'), implode(',', bArr('control')), b('degrees-type'), implode(',', bArr('degrees')), b('majors-type'), implode(',', bArr('majors')), bInt('black'), bInt('hospital'), isChecked('hospital-missing'), bInt('med-deg'), isChecked('med-deg-missing'), bInt('tribal'), bInt('public'), bInt('sat-min'), bInt('sat-max'), bInt('sat-mt-min'), bInt('sat-mt-max'), bInt('sat-cr-min'), bInt('sat-cr-max'), bInt('sat-wr-min'), bInt('sat-wr-max'), bInt('act-min'), bInt('act-max'), bInt('act-en-min'), bInt('act-en-max'), bInt('act-mt-min'), bInt('act-mt-max'), bInt('act-wr-min'), bInt('act-wr-max'), bInt('accept-min'), bInt('accept-max'), bInt('male-min'), bInt('male-max'), bInt('housing'), isChecked('housing-missing'), bInt('board'), isChecked('board-missing'), bInt('campus-required'), isChecked('campus-required-missing'), implode(',', bArr('dist')));
      $stmt->execute();
      $stmt->close();

      require_once "util/schools.php";
      die();
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

  $stmt = $mysql->prepare("SELECT `loc_type`,`loc_setting`,`loc_dist_min`,`loc_dist_max`,`loc_dist_addr`,`loc_state`,`level_type`,`level`,`control_type`,`control`,`degrees_type`,`degrees`,`majors_type`,`majors`,`black`,`hospital`,`hospital_missing`,`med_deg`,`med_deg_missing`,`tribal`,`public`,`sat_min`,`sat_max`,`sat_mt_min`,`sat_mt_max`,`sat_cr_min`,`sat_cr_max`,`sat_wr_min`,`sat_wr_max`,`act_min`,`act_max`,`act_en_min`,`act_en_max`,`act_mt_min`,`act_mt_max`,`act_wr_min`,`act_wr_max`,`accept_min`,`accept_max`,`male_min`,`male_max`,`housing`,`housing_missing`,`board`,`board_missing`,`campus_required`,`campus_required_missing`,`dist` FROM `prefs` WHERE `id` = ?");
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
      "control-type" => NULL,
      "control" => NULL,
      "degrees-type" => NULL,
      "degrees" => NULL,
      "majors-type" => NULL,
      "majors" => NULL,
      "black" => NULL,
      "hospital" => NULL,
      "hospital-missing" => NULL,
      "med-deg" => NULL,
      "med-deg-missing" => NULL,
      "tribal" => NULL,
      "public" => NULL,
      "sat-min" => NULL,
      "sat-max" => NULL,
      "sat-mt-min" => NULL,
      "sat-mt-max" => NULL,
      "sat-cr-min" => NULL,
      "sat-cr-max" => NULL,
      "sat-wr-min" => NULL,
      "sat-wr-max" => NULL,
      "act-min" => NULL,
      "act-max" => NULL,
      "act-en-min" => NULL,
      "act-en-max" => NULL,
      "act-mt-min" => NULL,
      "act-mt-max" => NULL,
      "act-wr-min" => NULL,
      "act-wr-max" => NULL,
      "accept-min" => NULL,
      "accept-max" => NULL,
      "male-min" => NULL,
      "male-max" => NULL,
      "housing" => NULL,
      "housing-missing" => NULL,
      "board" => NULL,
      "board-missing" => NULL,
      "campus-required" => NULL,
      "campus-required-missing" => NULL,
      "dist" => NULL,
    );
  $stmt->bind_result($vars["loc-type"], $vars["loc-setting"], $vars["loc-dist-min"], $vars["loc-dist-max"], $vars["loc-dist-from"], $vars["loc-state"], $vars["level-type"], $vars["level"], $vars["control-type"], $vars["control"], $vars["degrees-type"], $vars["degrees"], $vars["majors-type"], $vars["majors"], $vars["black"], $vars["hospital"], $vars["hospital-missing"], $vars["med-deg"], $vars["med-deg-missing"], $vars["tribal"], $vars["public"], $vars["sat-min"], $vars["sat-max"], $vars["sat-mt-min"], $vars["sat-mt-max"], $vars["sat-cr-min"], $vars["sat-cr-max"], $vars["sat-wr-min"], $vars["sat-wr-max"], $vars["act-min"], $vars["act-max"], $vars["act-en-min"], $vars["act-en-max"], $vars["act-mt-min"], $vars["act-mt-max"], $vars["act-wr-min"], $vars["act-wr-max"], $vars["accept-min"], $vars["accept-max"], $vars["male-min"], $vars["male-max"], $vars["housing"], $vars["housing-missing"], $vars["board"], $vars["board-missing"], $vars["campus-required"], $vars["campus-required-missing"], $vars["dist"]);
  assert($stmt->fetch());
  $stmt->close();
  $vars["loc-setting"] = explode(",", $vars["loc-setting"]);
  $vars["loc-state"] = explode(",", $vars["loc-state"]);
  $vars["level"] = explode(",", $vars["level"]);
  $vars["control"] = explode(",", $vars["control"]);
  $vars["degrees"] = explode(",", $vars["degrees"]);
  $vars["majors"] = explode(",", $vars["majors"]);
  $vars["dist"] = explode(",", $vars["dist"]);

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
    if(valOf($var) == $name) {
      echo " checked";
    }
  }

  function loc($name) {
    checked($name, 'loc-type');
  }

  function lvl($name) {
    checked($name, 'level-type');
  }

  function ctr($name) {
    checked($name, 'control-type');
  }

  function deg($name) {
    checked($name, 'degrees-type');
  }

  function mjr($name) {
    checked($name, 'majors-type');
  }

  function selected($name, $val) {
    if(valOf($name) == $val) {
      echo " selected";
    }
  }

  function blk($val) {
    selected('black', $val);
  }

  function hos($val) {
    selected('hospital', $val);
  }

  function med($val) {
    selected('med-deg', $val);
  }

  function trb($val) {
    selected('tribal', $val);
  }

  function pub($val) {
    selected('public', $val);
  }

  function hsn($val) {
    selected('housing', $val);
  }

  function brd($val) {
    selected('board', $val);
  }

  function crq($val) {
    selected('campus-required', $val);
  }

  function checkedBasic($name) {
    global $vars;
    if(isChecked($name) || ($vars[$name] != NULL && $vars[$name] != 0)) {
      echo " checked";
    }
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
        <h1>Search for schools</h1>

        <form action="#" method="post" class="dash-form">
          <h2>By Name</h2>
          <?php if($error == 2): ?>
            <div class="error">You can only use alphanumeric characters in your search.</div>
          <?php endif; ?>
          <input type="text" class="form-control" name="name"<?php e0("name"); ?> />
          <input type="submit" class="btn btn-primary sbn" value="Search By Name" />
        </form>

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
          <label><input type="radio" name="control-type" value="none" onclick="updateControl()"<?php ctr('none'); ?> />No Preference</label>
          <div class="loc" id="control-type-none">Include all public and private schools.</div>
          <label><input type="radio" name="control-type" value="some" onclick="updateControl()"<?php ctr('some'); ?> />Preference</label>
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
          <label><input type="radio" name="degrees-type" value="none" onclick="updateDegrees()"<?php deg('none'); ?> />No Preference</label>
          <div class="loc" id="degrees-type-none">Include schools that grant all degrees and those that don't grant any.</div>
          <label><input type="radio" name="degrees-type" value="some" onclick="updateDegrees()"<?php deg('some'); ?> />Preference</label>
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

          <h2>Majors</h2>
          <label><input type="radio" name="majors-type" value="none" onclick="updateMajors()"<?php mjr('none'); ?> />No Preference</label>
          <div class="loc" id="majors-type-none">Include schools that offer any majors.</div>
          <label><input type="radio" name="majors-type" value="some" onclick="updateMajors()"<?php mjr('some'); ?> />Preference</label>
          <div class="major loc" id="majors-type-some">
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
                echo '<ul>';

                $stmt2 = $mysql->prepare("SELECT `id`,`name` FROM `majors` WHERE `section` = ?");
                $stmt2->bind_param("i", $secID);
                $stmt2->execute();
                $stmt2->store_result();

                $majorID = NULL;
                $majorName = NULL;
                $stmt2->bind_result($majorID, $majorName);

                while($stmt2->fetch()) {
                  $val = valOf('majors');
                  echo '<li class="major-' . $majorID . '"><label><input type="checkbox" name="majors[]" value="' . $majorID . '"' . (($val != NULL && in_array($majorID, $val)) ? ' checked' : '') . ' />' . $majorName . '</label></li>';
                }

                $stmt2->close();

                echo '</ul>';
                echo '</div>';

              }

              $stmt->close();

            ?>
          </div>

          <h2>History and Facts</h2>
          <table>
            <tr>
              <td><label for="black">Historically Black:</label></td>
              <td>
                <select name="black" id="black" class="form-control">
                  <option value="-1"<?php blk(-1); ?>>No Preference</option>
                  <option value="1"<?php blk(1); ?>>Yes</option>
                  <option value="0"<?php blk(0); ?>>No</option>
                </select>
              </td>
            </tr>
            <tr>
              <td><label for="hospital">Has Hospital:</label></td>
              <td>
                <select name="hospital" id="hospital" class="form-control">
                  <option value="0"<?php hos(0); ?>>No Preference</option>
                  <option value="1"<?php hos(1); ?>>Yes</option>
                  <option value="2"<?php hos(2); ?>>No</option>
                </select>
              </td>
            </tr>
            <tr>
              <td colspan="2"><label><input type="checkbox" name="hospital-missing" value="yes"<?php checkedBasic('hospital-missing'); ?> />Include Schools with Unknown Hospital Presence</label></td>
            </tr>
            <tr>
              <td><label for="med-deg">Grants Medical Degree:</label></td>
              <td>
                <select name="med-deg" id="med-deg" class="form-control">
                  <option value="0"<?php med(0); ?>>No Preference</option>
                  <option value="1"<?php med(1); ?>>Yes</option>
                  <option value="2"<?php med(2); ?>>No</option>
                </select>
              </td>
            </tr>
            <tr>
              <td colspan="2"><label><input type="checkbox" name="med-deg-missing" value="yes"<?php checkedBasic('med-deg-missing'); ?> />Inculde Schools with Unknown Medical Degree Grating Status</label></td>
            </tr>
            <tr>
              <td><label for="tribal">Tribal College or University:</label></td>
              <td>
                <select name="tribal" id="tribal" class="form-control">
                  <option value="-1"<?php trb(-1); ?>>No Preference</option>
                  <option value="1"<?php trb(1); ?>>Yes</option>
                  <option value="0"<?php trb(0); ?>>No</option>
                </select>
              </td>
            </tr>
            <tr>
              <td><label for="public">Open to the General Public:</label></td>
              <td>
                <select name="public" id="public" class="form-control">
                  <option value="-1"<?php pub(-1); ?>>No Preference</option>
                  <option value="1"<?php pub(1); ?>>Yes</option>
                  <option value="0"<?php pub(0); ?>>No</option>
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
              score("male", "Percent Male", 0, 100);
            ?>
          </table>

          <h2>Campus Life</h2>
          <table>
            <tr>
              <!-- TODO: implement this part with connection to db -->
              <td><label for="housing">On-Campus Housing Provided:</label></td>
              <td>
                <select name="housing" id="housing" class="form-control">
                  <option value="0"<?php hsn(0); ?>>No Preference</option>
                  <option value="1"<?php hsn(1); ?>>Yes</option>
                  <option value="2"<?php hsn(2); ?>>No</option>
                </select>
              </td>
            </tr>
            <tr>
              <td colspan="2"><label><input type="checkbox" name="housing-missing" value="yes"<?php checkedBasic('housing-missing'); ?> />Include Schools with Unknown Housing Status</label></td>
            </tr>
            <tr>
              <td><label for="board">Meal Plan Available:</label></td>
              <td>
                <select name="board" id="board" class="form-control">
                  <option value="0"<?php brd(0); ?>>No Preference</option>
                  <option value="1"<?php brd(1); ?>>Yes</option>
                  <option value="3"<?php brd(3); ?>>No</option>
                </select>
              </td>
            </tr>
            <tr>
              <td colspan="2"><label><input type="checkbox" name="board-missing" value="yes"<?php checkedBasic('board-missing'); ?> />Include Schools with Unknown Meal Plan Status</label></td>
            </tr>
            <tr>
              <td><label for="campus-required">Required to Live on Campus:</label></td>
              <td>
                <select name="campus-required" id="campus-required" class="form-control">
                  <option value="0"<?php crq(0); ?>>No Preference</option>
                  <option value="1"<?php crq(1); ?>>Yes</option>
                  <option value="2"<?php crq(2); ?>>No</option>
                </select>
              </td>
            </tr>
            <tr>
              <td colspan="2"><label><input type="checkbox" name="campus-required-missing" value="yes"<?php checkedBasic('campus-required-missing'); ?> />Include Schools with Unknown Requirement</label></td>
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
              <td colspan="2"><?php checkBox("missing", "Include Schools with Unknown Distance Status", "dist"); ?></td>
            </tr>
          </table>

          <input type="submit" class="btn btn-primary" value="Search" />
        </form>
      </div>

    </div>
    <script src="js/search.js"></script>
<?php
  require_once "util/footer.php";
?>