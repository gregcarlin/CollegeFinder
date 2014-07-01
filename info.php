<?php
  $page = 1;
  $extra = '<link href="styles/info.css" rel="stylesheet" />';
  require_once "util/header-signedin.php";

  $stmt = $mysql->prepare("SELECT `fname`,`lname`,`email`,`sat`,`sat_mt`,`sat_cr`,`sat_wr`,`act`,`act_en`,`act_mt`,`act_rd`,`act_sc`,`act_wr`,`gpa_weight`,`gpa_noweight` FROM `students` WHERE `id` = ?");
  $stmt->bind_param("i", $id);
  $stmt->execute();
  /*$fname = NULL;
  $lname = NULL;
  $email = NULL;
  $sat = NULL;
  $satMT = NULL;
  $satCR = NULL;
  $satWR = NULL;
  $act = NULL;
  $actEN = NULL;
  $actMT = NULL;
  $actRD = NULL;
  $actSC = NULL;
  $actWR = NULL;
  $gpaWeight = NULL;
  $gpaNoWeight = NULL;*/
  $vars = array(
      "fname" => NULL,
      "lname" => NULL,
      "email" => NULL,
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
  //$stmt->bind_result($fname, $lname, $email, $sat, $satMT, $sat);
  $stmt->bind_result($vars["fname"], $vars["lname"], $vars["email"], $vars["sat"], $vars["satMT"], $vars["satCR"], $vars["satWR"], $vars["act"], $vars["actEN"], $vars["actMT"], $vars["actRD"], $vars["actSC"], $vars["actWR"], $vars["gpaWeight"], $vars["gpaNoWeight"]);
  assert($stmt->fetch());
  //var_dump($vars);

  function a($name, $title) {
    global $vars;
    echo '<tr>';
    echo '<td><label for="' . $name . '">' . $title . '</label></td>';
    echo '<td><input type="text" class="form-control" id="' . $name . '" name="' . $name . '" value="' . $vars[$name] . '" /></td>';
    echo '</tr>';
  }
?>

    <div class="container">

      <div class="starter-template">
        <h1>Update your information</h1>

        <table>
          <!--<tr>
            <td><label for="fname">First Name:</label></td>
            <td><input type="text" class="form-control" id="fname" name="fname" /></td>
          </tr>
          <tr>
            <td><label for="lname">Last Name:</label></td>
            <td><input type="text" class="form-control" id="lname" name="lname" /></td>
          </tr>
          <tr>
            <td><label for=""></label></td>
            <td><input type="text" class="form-control" id="" name="" /></td>
          </tr>
          <tr>
            <td><label for=""></label></td>
            <td><input type="text" class="form-control" id="" name="" /></td>
          </tr>-->
          <?php
            a("fname", "First Name");
            a("lname", "Last Name");
            a("email", "Email Address");
            a("sat", "Best Single-Sitting SAT");
            a("satMT", "Best SAT Math");
            a("satCR", "Best SAT Reading");
            a("satWR", "Best SAT Writing");
            a("act", "Best Single-Sitting ACT");
          ?>
        </table>
      </div>

    </div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
  </body>
</html>
