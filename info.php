<?php
  $page = 1;
  $mode = 1;
  $title = 'Personal Info';
  $extra = '<link href="styles/info.css" rel="stylesheet" />';
  require_once "util/header.php";

  $changes = false;
  $error = 0;
  if(anySet('fname', 'lname', 'sat', 'satMT', 'satCR', 'satWR', 'act', 'actEN', 'actMT', 'actRD', 'actSC', 'actWR', 'gpaWeight', 'gpaNoWeight')) {
    $fname = n('fname');
    $lname = n('lname');
    if($fname == NULL || $lname == NULL) {
      $error = 1;
    } else {

      $stmt = $mysql->prepare("UPDATE `students` SET `fname` = ?, `lname` = ?, `sat` = ?, `sat_mt` = ?, `sat_cr` = ?, `sat_wr` = ?, `act` = ?, `act_en` = ?, `act_mt` = ?, `act_rd` = ?, `act_sc` = ?, `act_wr` = ?, `gpa_weight` = ?, `gpa_noweight` = ? WHERE `id` = ?");
      $stmt->bind_param('ssssssssssssssi', $fname, $lname, n('sat'), n('satMT'), n('satCR'), n('satWR'), n('act'), n('actEN'), n('actMT'), n('actRD'), n('actSC'), n('actWR'), n('gpaWeight'), n('gpaNoWeight'), $id);
      $stmt->execute();
      $stmt->close();
      $changes = true;

    }
  }

  $stmt = $mysql->prepare("SELECT `fname`,`lname`,`sat`,`sat_mt`,`sat_cr`,`sat_wr`,`act`,`act_en`,`act_mt`,`act_rd`,`act_sc`,`act_wr`,`gpa_weight`,`gpa_noweight` FROM `students` WHERE `id` = ?");
  $stmt->bind_param("i", $id);
  $stmt->execute();
  $vars = array(
      "fname" => NULL,
      "lname" => NULL,
      "sat" => NULL,
      "satMT" => NULL,
      "satCR" => NULL,
      "satWR" => NULL,
      "act" => NULL,
      "actEN" => NULL,
      "actMT" => NULL,
      "actRD" => NULL,
      "actSC" => NULL,
      "actWR" => NULL,
      "gpaWeight" => NULL,
      "gpaNoWeight" => NULL,
    );
  $stmt->bind_result($vars["fname"], $vars["lname"], $vars["sat"], $vars["satMT"], $vars["satCR"], $vars["satWR"], $vars["act"], $vars["actEN"], $vars["actMT"], $vars["actRD"], $vars["actSC"], $vars["actWR"], $vars["gpaWeight"], $vars["gpaNoWeight"]);
  assert($stmt->fetch());
  $stmt->close();

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
        <h1>Update your information</h1>

        <form action="#" method="post">
          <table>
            <?php
              if($changes) {
                echo '<tr>';
                echo '<td colspan="2" class="success">Changes saved.</td>';
                echo '</tr>';
              } else if($error == 1) {
                echo '<tr>';
                echo '<td colspan="2" class="error">You cannot leave your name blank.</td>';
                echo '</tr>';
              }
              a("fname", "First Name");
              a("lname", "Last Name");
              doubleBlank();
              a("sat", "Best Single-Sitting SAT");
              blank();
              a("satMT", "Best SAT Math");
              a("satCR", "Best SAT Reading");
              a("satWR", "Best SAT Writing");
              doubleBlank();
              a("act", "Best Single-Sitting ACT");
              blank();
              a("actEN", "Best ACT English");
              a("actMT", "Best ACT Math");
              a("actRD", "Best ACT Reading");
              a("actSC", "Best ACT Science");
              a("actWR", "Best ACT Writing");
              doubleBlank();
              a("gpaWeight", "Unweighted GPA");
              a("gpaNoWeight", "Weighted GPA");
              /*doubleBlank();*/
            ?>
            <tr>
              <td colspan="2"><input type="submit" class="btn btn-primary" value="Save Changes" /></td>
            </tr>
          </table>
        </form>
      </div>

    </div>
<?php
  require_once "util/footer.php";
?>