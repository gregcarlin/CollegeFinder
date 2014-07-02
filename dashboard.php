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
?>

    <div class="container">

      <div class="starter-template">
        <h1>Change your preferences</h1>

        <form action="#" method="post" class="dash-form">
          <h2>Location</h2>
          <label><input type="radio" name="loc-type" value="none" />No Preference</label>
          <label><input type="radio" name="loc-type" value="setting" />Setting</label>
          <table>
            <tr>
              <td>
                <label><input type="checkbox" name="loc-setting[]" value="11" />Large City</label>
                <label><input type="checkbox" name="loc-setting[]" value="12" />Midsize City</label>
                <label><input type="checkbox" name="loc-setting[]" value="13" />Small City</label>
                <label><input type="checkbox" name="loc-setting[]" value="21" />Large Suburb</label>
                <label><input type="checkbox" name="loc-setting[]" value="22" />Midsize Suburb</label>
                <label><input type="checkbox" name="loc-setting[]" value="23" />Small Suburb</label>
              </td>
              <td>
                <label><input type="checkbox" name="loc-setting[]" value="31" />Fringe Town</label>
                <label><input type="checkbox" name="loc-setting[]" value="32" />Distant Town</label>
                <label><input type="checkbox" name="loc-setting[]" value="33" />Remote Town</label>
                <label><input type="checkbox" name="loc-setting[]" value="41" />Fringe Rural</label>
                <label><input type="checkbox" name="loc-setting[]" value="42" />Distant Rural</label>
                <label><input type="checkbox" name="loc-setting[]" value="43" />Remote Rural</label>
              </td>
            </tr>
            <tr>
              <td colspan="2">
                <label><input type="checkbox" name="loc-setting[]" value="-3" />Include Schools with Unknown Settings</label>
              </td>
            </tr>
          </table>
          <label><input type="radio" name="loc-type" value="distance" />Distance</label>
          <div>
            Between <input type="text" name="loc-dist-min" placeholder="0" class="form-control" /> and <input type="text" name="loc-dist-max" placeholder="25000" class="form-control" /> miles away from <input type="text" name="loc-dist-from" placeholder="Address/Zip Code" class="form-control addr" />
          </div>
          <label><input type="radio" name="loc-type" value="state" />State</label>
          <table class="sectioned">
            <tr>
              <td colspan="2"><h3>New England</h3></td>
            </tr>
            <tr>
              <td>
                <label><input type="checkbox" name="loc-state[]" value="CT" />Connecticut</label>
                <label><input type="checkbox" name="loc-state[]" value="ME" />Maine</label>
                <label><input type="checkbox" name="loc-state[]" value="MA" />Massachusetts</label>
              </td>
              <td>
                <label><input type="checkbox" name="loc-state[]" value="NH" />New Hampshire</label>
                <label><input type="checkbox" name="loc-state[]" value="RI" />Rhode Island</label>
                <label><input type="checkbox" name="loc-state[]" value="VT" />Vermont</label>
              </td>
            </tr>
            <tr>
              <td colspan="2"><h3>Mid-Atlantic</h3></td>
            </tr>
            <tr>
              <td>
                <label><input type="checkbox" name="loc-state[]" value="NJ" />New Jersey</label>
                <label><input type="checkbox" name="loc-state[]" value="NY" />New York</label>
              </td>
              <td>
                <label><input type="checkbox" name="loc-state[]" value="PA" />Pennsyvlania</label>
              </td>
            </tr>
            <tr>
              <td colspan="2"><h3>East North Central</h3></td>
            </tr>
            <tr>
              <td>
                <label><input type="checkbox" name="loc-state[]" value="IL" />Illinois</label>
                <label><input type="checkbox" name="loc-state[]" value="IN" />Indiana</label>
                <label><input type="checkbox" name="loc-state[]" value="MI" />Michigan</label>
              </td>
              <td>
                <label><input type="checkbox" name="loc-state[]" value="OH" />Ohio</label>
                <label><input type="checkbox" name="loc-state[]" value="WI" />Wisconsin</label>
            </tr>
            <tr>
              <td colspan="2"><h3>West North Central</h3></td>
            </tr>
            <tr>
              <td>
                <label><input type="checkbox" name="loc-state[]" value="IA" />Iowa</label>
                <label><input type="checkbox" name="loc-state[]" value="KS" />Kansas</label>
                <label><input type="checkbox" name="loc-state[]" value="MN" />Minnesota</label>
                <label><input type="checkbox" name="loc-state[]" value="MO" />Missouri</label>
              </td>
              <td>
                <label><input type="checkbox" name="loc-state[]" value="NE" />Nebraska</label>
                <label><input type="checkbox" name="loc-state[]" value="ND" />North Dakota</label>
                <label><input type="checkbox" name="loc-state[]" value="SD" />South Dakota</label>
              </td>
            </tr>
            <tr>
              <td colspan="2"><h3>South Atlantic</h3></td>
            </tr>
            <tr>
              <td>
                <label><input type="checkbox" name="loc-state[]" value="DE" />Delaware</label>
                <label><input type="checkbox" name="loc-state[]" value="FL" />Florida</label>
                <label><input type="checkbox" name="loc-state[]" value="GA" />Georgia</label>
                <label><input type="checkbox" name="loc-state[]" value="MD" />Maryland</label>
                <label><input type="checkbox" name="loc-state[]" value="NC" />North Carolina</label>
              </td>
              <td>
                <label><input type="checkbox" name="loc-state[]" value="SC" />South Carolina</label>
                <label><input type="checkbox" name="loc-state[]" value="VA" />Virginia</label>
                <label><input type="checkbox" name="loc-state[]" value="DC" />Washington, D.C.</label>
                <label><input type="checkbox" name="loc-state[]" value="WV" />West Virginia</label>
              </td>
            </tr>
            <tr>
              <td colspan="2"><h3>East South Central</h3></td>
            </tr>
            <tr>
              <td>
                <label><input type="checkbox" name="loc-state[]" value="AL" />Alabama</label>
                <label><input type="checkbox" name="loc-state[]" value="KY" />Kentucky</label>
              </td>
              <td>
                <label><input type="checkbox" name="loc-state[]" value="MS" />Mississippi</label>
                <label><input type="checkbox" name="loc-state[]" value="TN" />Tennessee</label>
              </td>
            </tr>
            <tr>
              <td colspan="2"><h3>West South Central</h3></td>
            </tr>
            <tr>
              <td>
                <label><input type="checkbox" name="loc-state[]" value="AR" />Arkansas</label>
                <label><input type="checkbox" name="loc-state[]" value="LA" />Louisiana</label>
              </td>
              <td>
                <label><input type="checkbox" name="loc-state[]" value="OK" />Oklahoma</label>
                <label><input type="checkbox" name="loc-state[]" value="TX" />Texas</label>
              </td>
            </tr>
            <tr>
              <td colspan="2"><h3>Mountain</h3></td>
            </tr>
            <tr>
              <td>
                <label><input type="checkbox" name="loc-state[]" value="AZ" />Arizona</label>
                <label><input type="checkbox" name="loc-state[]" value="CO" />Colorado</label>
                <label><input type="checkbox" name="loc-state[]" value="ID" />Idaho</label>
                <label><input type="checkbox" name="loc-state[]" value="MT" />Montana</label>
              </td>
              <td>
                <label><input type="checkbox" name="loc-state[]" value="NV" />Nevada</label>
                <label><input type="checkbox" name="loc-state[]" value="NM" />New Mexico</label>
                <label><input type="checkbox" name="loc-state[]" value="UT" />Utah</label>
                <label><input type="checkbox" name="loc-state[]" value="WY" />Wyoming</label>
              </td>
            </tr>
            <tr>
              <td colspan="2"><h3>Pacific</h3></td>
            </tr>
            <tr>
              <td>
                <label><input type="checkbox" name="loc-state[]" value="AK" />Alaska</label>
                <label><input type="checkbox" name="loc-state[]" value="CA" />California</label>
                <label><input type="checkbox" name="loc-state[]" value="HI" />Hawaii</label>
              </td>
              <td>
                <label><input type="checkbox" name="loc-state[]" value="OR" />Oregon</label>
                <label><input type="checkbox" name="loc-state[]" value="WA" />Washington</label>
              </td>
            </tr>
            <tr>
              <td colspan="2"><h3>Other</h3></td>
            </tr>
            <tr>
              <td>
                <label><input type="checkbox" name="loc-state[]" value="AS" />American Samoa</label>
                <label><input type="checkbox" name="loc-state[]" value="FM" />Federated States of Micronesia</label>
                <label><input type="checkbox" name="loc-state[]" value="GU" />Guam</label>
                <label><input type="checkbox" name="loc-state[]" value="MH" />Marshall Islands</label>
              </td>
              <td>
                <label><input type="checkbox" name="loc-state[]" value="MP" />Northern Mariana Islands</label>
                <label><input type="checkbox" name="loc-state[]" value="PW" />Palau</label>
                <label><input type="checkbox" name="loc-state[]" value="PR" />Puerto Rico</label>
                <label><input type="checkbox" name="loc-state[]" value="VI" />Virgin Islands</label>
              </td>
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
  </body>
</html>