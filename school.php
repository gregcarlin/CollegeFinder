<?php
  if(!isset($_GET['id'])) {
    header("Location: schools.php");
    die();
  }

  $page = 2;
  $extra = '<link href="styles/school.css" rel="stylesheet" />';
  require_once "util/header-signedin.php";

  $stmt = $mysql->prepare("SELECT * FROM `schools`,`supplementary` WHERE `schools`.`id` = `supplementary`.`id` AND `schools`.`id` = ?");
  $stmt->bind_param("i", $_GET['id']);
  $stmt->execute();

  $result = getResult($stmt)[0];
  $stmt->close();
  //var_dump($result);
?>

    <div class="container">

      <div class="starter-template">
        <?php if($result == NULL): ?>
          School not found. Please <a href="schools.php">go back</a> and try again.
        <?php else: ?>
          <h1><?php echo $result["name"]; ?></h1>
          <?php
            if($result["alias"]) {
              echo "<div>";
              echo "Also known as ";
              $all = "";
              $split = preg_split("/\|/", $result["alias"]);
              $len = count($split);
              for($i = 0; $i<$len-1; $i++) {
                $all .= '&ldquo;' . trim($split[$i]) . '&rdquo;, and ';
              }
              echo substr($all, 0, strlen($all) - 6) . ' and ' . '&ldquo;' . trim($split[$len-1]) . '&rdquo;';
              echo "</div>";
            }
          ?>
          <div class="left">
            <h2>Location</h2>
            <?php
              echo "<p>";
              echo h($result["address"], "Street") . "<br />";
              echo $result["city"] . ", " . $result["state"] . " " . $result["zip"];
              echo "</p>";

              echo "<p>";
              echo $result["county"] . "<br />";
              $urbArr = array(11 => "Large City", 12 => "Midsize City", 13 => "Small City", 21 => "Large Suburb", 22 => "Midsize Suburb", 23 => "Small Suburb", 31 => "Fringe Town", 32 => "Distant Town", 33 => "Remote Town", 41 => "Fringe Rural", 42 => "Distant Rural", 43 => "Remote Rural", -3 => "Setting Unknown");
              echo $urbArr[$result["urbanization"]] . "<br />";
              echo "Congressional District: " . $result["congress_district"];
              echo "</p>";

              $lon = $result["longitude"];
              $lat = $result["latitude"];
              $lon = $lon < 0 ? (-$lon . "&#176; W") : ($lon . "&#176; E");
              $lat = $lat < 0 ? (-$lat . "&#176; S") : ($lat . "&#176; N");
              $coord = $lat . " " . $lon;
              if($lon != 0 || $lat != 0) echo '<iframe width="300" height="300" frameborder="0" style="border:0" src="https://www.google.com/maps/embed/v1/place?key=AIzaSyADx_CXHX0I2ezcwRsGboW2X3Diucufi7w&q=' . $coord . '"></iframe>';
            ?>
          </div>
          <div class="mid">
            <h2>Statistics</h2>
            <?php
              echo "<table>";
              echo "<tr><td>Applicants:</td><td>" . h($result["applied"]) . "</td></tr>";
              echo "<tr><td>Admitted:</td><td>" . h($result["admitted"]) . "</td></tr>";
              $accept = round($result["admitted"] / $result["applied"] * 100);
              echo "<tr><td>Acceptance Rate:</td><td>" . ($accept ? ($accept . "%") : "Unknown") . "</td></tr>";
              echo "<tr><td>Full-Time Enrolled:</td><td>" . h($result["enroll_ft"]) . "</td></tr>";
              echo "<tr><td>Part-Time Enrolled:</td><td>" . h($result["enroll_pt"]) . "</td></tr>";
              $enroll = round($result["enroll"] / $result["admitted"] * 100);
              echo "<tr><td>Enroll Rate:</td><td>" . ($enroll ? ($enroll . "%") : "Unknown") . "</td></tr>";
              echo "</table>";

              echo "<table>";
              echo "<tr><td>Male Applicants:</td><td>" . h($result["applied_m"]) . "</td></tr>";
              echo "<tr><td>Admitted Males:</td><td>" . h($result["admit_m"]) . "</td></tr>";
              $acceptM = round($result["admit_m"] / $result["applied_m"] * 100);
              echo "<tr><td>Male Acceptance Rate:</td><td>" . ($acceptM ? ($acceptM . "%") : "Unknown") . "</td></tr>";
              echo "<tr><td>Full-Time Enrolled Males:</td><td>" . h($result["enroll_full_m"]) . "</td></tr>";
              echo "<tr><td>Part-Time Enrolled Males:</td><td>" . h($result["enroll_part_m"]) . "</td></tr>";
              $enrollM = round($result["enroll_m"] / $result["admit_m"] * 100);
              echo "<tr><td>Male Enroll Rate:</td><td>" . ($enrollM ? ($enrollM . "%") : "Unknown") . "</td></tr>";
              echo "</table>";

              echo "<table>";
              echo "<tr><td>Female Applicants:</td><td>" . h($result["applied_f"]) . "</td></tr>";
              echo "<tr><td>Admitted Females:</td><td>" . h($result["admit_f"]) . "</td></tr>";
              $acceptF = round($result["admit_f"] / $result["applied_f"] * 100);
              echo "<tr><td>Female Acceptance Rate:</td><td>" . ($acceptF ? ($acceptF . "%") : "Unknown") . "</td></tr>";
              echo "<tr><td>Full-Time Enrolled Females:</td><td>" . h($result["enroll_full_f"]) . "</td></tr>";
              echo "<tr><td>Part-Time Enrolled Females:</td><td>" . h($result["enroll_part_f"]) . "</td></tr>";
              $enrollF = round($result["enroll_f"] / $result["admit_f"] * 100);
              echo "<tr><td>Female Enroll Rate:</td><td>" . ($enrollF ? ($enrollF . "%") : "Unknown") . "</td></tr>";
              echo "</table>";

              echo "<table>";
              $disabled = round($result["disabled"] * 100);
              echo "<tr><td>Disabled:</td><td>" . ($disabled ? ($disabled . "%") : "Unknown") . "</td></tr>";
              echo "</table>";
            ?>
          </div>
          <div class="right">
            <h2>Facts</h2>
            <?php
              echo "<p>";
              $levelArr = array(1 => "4+ Years", 2 => "At least 2 but less than 4 years", 3 => "Less than 2 years", -3 => "Unknown Level");
              echo $levelArr[$result["level"]] . "<br />";
              $controlArr = array(1 => "Public", 2 => "Private (Non-Profit)", 3 => "Private (For-Profit)", -3 => "Unknown Control");
              echo $controlArr[$result["control"]] . "<br />";
              $maxDegArr = array(11 => "Doctor's - Research/Scholarship and Professional Practice", 12 => "Doctor's - Research/Scholarship", 13 => "Doctor's - Professional Practice", 14 => "Doctor's", 20 => "Master's", 30 => "Bachelor's", 40 => "Associate's", 0 => "None", -3 => "Unknown");
              echo "Highest Degree Offered: " . $maxDegArr[$result["max_degree"]] . "<br />";
              echo "</p>";

              echo "<p>";
              echo ($result["historically_black"] ? "" : "Not ") . "Historically Black<br />";
              $hosArr = array(1 => "Has a Hospital", 2 => "Does not have a Hospital", -1 => "Hospital Presence Unknown");
              $hosArr[-2] = $hosArr[-1];
              echo $hosArr[$result["has_hospital"]] . "<br />";
              echo ($result["tribal"] ? "A" : "Not a") . " Tribal College or University<br />";
              echo ($result["open_to_public"] ? "" : "Not ") . "Open to the General Public<br />";
              $closed = $result["closed"];
              echo ($closed == NULL ? ("Currently Open") : ("Closed on " . $closed)) . "<br />";
              echo ($result["land_grant"] ? "A" : "Not a") . " Land Grant University<br />";
              if($result["all_dist"] == 1) {
                echo "All Programs Offered via Distance";
              } else if($result["under_dist"] == 1) {
                echo "Undergraduate Programs Offered via Distance";
              } else if($result["grad_dist"] == 1) {
                echo "Graduate Programs Offered via Distance";
              } else if($result["no_dist"] == 1) {
                echo "No Programs Offered via Distance";
              } else {
                echo "Programs Offered via Distance Unknown";
              }
              echo "</p>";

              echo "<p>";
              $boardArr = array(1 => "Offers a Meal Plan", 3 => "Does not Offer a Meal Plan", -1 => "Meal Plan Presence Unknown");
              $boardArr[2] = $boardArr[1];
              $boardArr[-2] = $boardArr[-1];
              echo $boardArr[$result["board_provided"]] . "<br />";
              $campArr = array(1 => "Required to Live on Campus", 2 => "Not Required to Live on Campus", -1 => "Campus Requirement Unknown");
              $campArr[-2] = $campArr[-1];
              echo $campArr[$result["campus_required"]] . "<br />";
              echo "</p>";
            ?>

            <h2>Contact</h2>
            <?php
              echo "<p>";
              echo h(p($result["phone"]), "Phone") . "<br />";
              echo h(p($result["fax"]), "Fax") . "<br />";
              echo "</p>";

              echo "<p>";
              echo u($result["website"], "Main Website") . "<br />";
              echo u($result["admis_url"], "Admissions") . "<br />";
              echo u($result["finance_url"], "Financial Aid") . "<br />";
              echo u($result["net_price_url"], "Net Price Calculator") . "<br />";
              echo u($result["app_url"], "Online Application") . "<br />";
              echo "</p>";
            ?>
          </div>
        <?php endif; ?>
      </div>

    </div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="js/jquery.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
  </body>
</html>