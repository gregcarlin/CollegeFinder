<?php
  $page = 0;
  $extra = '<link href="styles/dash.css" rel="stylesheet" />';
  require_once "util/header-signedin.php";

  function a($name, $title) {
    global $vars;
    echo '<tr>';
    echo '<td><label for="' . $name . '">' . $title . '</label></td>';
    echo '<td><input type="text" class="form-control" id="' . $name . '" name="' . $name . '" value="' . (isset($_POST[$name]) ? $_POST[$name] : $vars[$name]) . '" /></td>';
    echo '</tr>';
  }

  function blank() {
    echo '<tr>';
    echo '<td colspan="2" class="blank"></td>';
    echo '</tr>';
  }

  function doubleBlank() {
    echo '<tr>';
    echo '<td colspan="2" class="double-blank"><span></span></td>';
    echo '</tr>';
  }

  function setting($value, $title) {
    echo '<label><input type="checkbox" name="loc-setting[]" value="' . $value . '"' . ((isset($_POST['loc-setting']) && in_array($value, $_POST['loc-setting'])) ? ' checked' : '') . ' />' . $title . '</label>';
  }

  function settingArr($arr) {
    foreach($arr as $key => $val) {
      setting($key, $val);
    }
  }

  function state($value, $title) {
    echo '<label><input type="checkbox" name="loc-state[]" value="' . $value . '"' . ((isset($_POST['loc-state']) && in_array($value, $_POST['loc-state'])) ? ' checked' : '') . ' />' . $title . '</label>';
  }

  function stateArr($arr) {
    foreach($arr as $key => $val) {
      state($key, $val);
    }
  }
?>

    <div class="container">

      <div class="starter-template">
        <h1>Change your preferences</h1>

        <form action="#" method="post" class="dash-form">
          <h2>Location</h2>
          <label><input type="radio" name="loc-type" value="none" onclick="updateLocation()" />No Preference</label>
          <div id="none">
            Include schools from the entire United States.
          </div>
          <label><input type="radio" name="loc-type" value="setting" onclick="updateLocation()" />Setting</label>
          <table id="setting">
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
          <label><input type="radio" name="loc-type" value="distance" onclick="updateLocation()" />Distance</label>
          <div id="distance">
            Between <input type="text" name="loc-dist-min" placeholder="0" class="form-control" /> and <input type="text" name="loc-dist-max" placeholder="25000" class="form-control" /> miles away from <input type="text" name="loc-dist-from" placeholder="Address/Zip Code" class="form-control addr" />
          </div>
          <label><input type="radio" name="loc-type" value="state" onclick="updateLocation()" />State</label>
          <table class="sectioned" id="state">
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

          <h2>Hi</h2>
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