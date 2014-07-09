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
              echo "<ul>";
              echo "<li>" . h($result["address"], "Street") . "</li>";
              echo "<li>" . $result["city"] . ", " . $result["state"] . " " . $result["zip"] . "</li>";
              echo "</ul>";

              echo "<ul>";
              echo "<li>" . $result["county"] . "</li>";
              $urbArr = array(11 => "Large City", 12 => "Midsize City", 13 => "Small City", 21 => "Large Suburb", 22 => "Midsize Suburb", 23 => "Small Suburb", 31 => "Fringe Town", 32 => "Distant Town", 33 => "Remote Town", 41 => "Fringe Rural", 42 => "Distant Rural", 43 => "Remote Rural", -3 => "Setting Unknown");
              echo "<li>" . $urbArr[$result["urbanization"]] . "</li>";
              echo "<li>Congressional District: " . $result["congress_district"] . "</li>";
              echo "</ul>";

              $lon = $result["longitude"];
              $lat = $result["latitude"];
              $lon = $lon < 0 ? (-$lon . "&#176; W") : ($lon . "&#176; E");
              $lat = $lat < 0 ? (-$lat . "&#176; S") : ($lat . "&#176; N");
              $coord = $lat . " " . $lon;
              if($lon != 0 || $lat != 0) echo '<iframe width="275" height="275" frameborder="0" style="border:0" src="https://www.google.com/maps/embed/v1/place?key=AIzaSyADx_CXHX0I2ezcwRsGboW2X3Diucufi7w&q=' . $coord . '"></iframe>';
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
              $satNum = $result["sat_num"];
              echo "<tr><td>Applicants Submitting SAT Scores:</td><td>" . ($satNum == NULL ? "Unknown" : ($satNum . " (" . round($result["sat_prop"]*100) . "%)")) . "</td></tr>";
              $actNum = $result["act_num"];
              echo "<tr><td>Applicants Submitting ACT Scores:</td><td>" . ($actNum == NULL ? "Unknown" : ($actNum . " (" . round($result["act_prop"]*100) . "%)")) . "</td></tr>";
              echo "</table>";

              echo "<table>";
              $sat25 = $result["sat_cr_25"] + $result["sat_mt_25"] + $result["sat_wr_25"];
              $sat75 = $result["sat_cr_75"] + $result["sat_mt_75"] + $result["sat_wr_75"];
              echo "<tr><td>SAT Range:</td><td>" . r($sat25, $sat75) . "</td></tr>";
              echo "<tr><td>SAT Critical Reading:</td><td>" . r($result["sat_cr_25"], $result["sat_cr_75"]) . "</td></tr>";
              echo "<tr><td>SAT Math:</td><td>" . r($result["sat_mt_25"], $result["sat_mt_75"]) . "</td></tr>";
              echo "<tr><td>SAT Writing:</td><td>" . r($result["sat_wr_25"], $result["sat_wr_75"]) . "</td></tr>";
              echo "<tr><td>ACT Range:</td><td>" . r($result["act_cm_25"], $result["act_cm_75"]) . "</td></tr>";
              echo "<tr><td>ACT English:</td><td>" . r($result["act_en_25"], $result["act_en_75"]) . "</td></tr>";
              echo "<tr><td>ACT Math:</td><td>" . r($result["act_mt_25"], $result["act_mt_75"]) . "</td></tr>";
              echo "<tr><td>ACT Writing:</td><td>" . r($result["act_wr_25"], $result["act_wr_75"]) . "</td></tr>";
              echo "</table>";

              echo "<table>";
              $disabled = round($result["disabled"] * 100);
              echo "<tr><td>Disabled:</td><td>" . ($disabled ? ($disabled . "%") : "Unknown") . "</td></tr>";
              echo "<tr><td>Application Fee (undergrads):</td><td>" . m($result["app_fee_u"]) . "</td></tr>";
              echo "<tr><td>Application Fee (grads):</td><td>" . m($result["app_fee_g"]) . "</td></tr>";
              echo "<tr><td>Dormitory Capacity:</td><td>" . h($result["room_cap"]) . "</td></tr>";
              echo "<tr><td>Cost of Room:</td><td>" . m($result["room_cost"]) . "</td></tr>";
              echo "<tr><td>Cost of Board:</td><td>" . m($result["board_cost"]) . "</td></tr>";
              echo "<tr><td>Total Tuition:</td><td>" . h($result["total_cost"]) . "</td></tr>";
              echo "</table>";
            ?>
          </div>
          <div class="right">
            <h2>Facts</h2>
            <?php
              echo "<ul>";
              $levelArr = array(1 => "4+ Years", 2 => "At least 2 but less than 4 years", 3 => "Less than 2 years", -3 => "Unknown Level");
              echo "<li>" . $levelArr[$result["level"]] . "</li>";
              $controlArr = array(1 => "Public", 2 => "Private (Non-Profit)", 3 => "Private (For-Profit)", -3 => "Unknown Control");
              echo "<li>" . $controlArr[$result["control"]] . "</li>";
              $maxDegArr = array(11 => "Doctor's - Research/Scholarship and Professional Practice", 12 => "Doctor's - Research/Scholarship", 13 => "Doctor's - Professional Practice", 14 => "Doctor's", 20 => "Master's", 30 => "Bachelor's", 40 => "Associate's", 0 => "None", -3 => "Unknown");
              echo "<li>Highest Degree Offered: " . $maxDegArr[$result["max_degree"]] . "</li>";
              echo "</ul>";

              echo "<ul>";
              echo "<li>" . ($result["historically_black"] ? "" : "Not ") . "Historically Black</li>";
              $hosArr = array(1 => "Has a Hospital", 2 => "Does not have a Hospital", -1 => "Hospital Presence Unknown");
              $hosArr[-2] = $hosArr[-1];
              echo "<li>" . $hosArr[$result["has_hospital"]] . "</li>";
              echo "<li>" . ($result["tribal"] ? "A" : "Not a") . " Tribal College or University</li>";
              echo "<li>" . ($result["open_to_public"] ? "" : "Not ") . "Open to the General Public</li>";
              $closed = $result["closed"];
              echo "<li>" . ($closed == NULL ? ("Currently Open") : ("Closed on " . $closed)) . "</li>";
              echo "<li>" . ($result["land_grant"] ? "A" : "Not a") . " Land Grant University</li>";
              echo "<li>";
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
              echo "</li>";
              echo "</ul>";

              echo "<ul>";
              $boardArr = array(1 => ("Offers a Meal Plan (" . h($result["meals_wk"]) . " meals per week)"), 3 => "Does not Offer a Meal Plan", -1 => "Meal Plan Presence Unknown");
              $boardArr[2] = $boardArr[1];
              $boardArr[-2] = $boardArr[-1];
              echo "<li>" . $boardArr[$result["board_provided"]] . "</li>";
              $campArr = array(1 => "Required to Live on Campus", 2 => "Not Required to Live on Campus", -1 => "Campus Requirement Unknown");
              $campArr[-2] = $campArr[-1];
              echo "<li>" . $campArr[$result["campus_required"]] . "</li>";
              echo "</ul>";
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