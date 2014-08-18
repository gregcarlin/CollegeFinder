<?php
  if(!isset($_GET['id'])) {
    header("Location: schools.php");
    die();
  }

  require_once "util/util.php";
  require_once "util/get-db.php";
  $stmt = $mysql->prepare("SELECT * FROM `schools`,`supplementary` WHERE `schools`.`id` = `supplementary`.`id` AND `schools`.`id` = ?");
  $stmt->bind_param("i", $_GET['id']);
  $stmt->execute();
  $result = getSchools(getResult($stmt))[0];
  $stmt->close();

  $page = 2;
  $mode = 0;
  $title = $result->name();
  $extra = '<link href="styles/school.css" rel="stylesheet" />';
  require_once "util/header.php";

  if($loggedIn) {
    $stmt = $mysql->prepare("SELECT `sat`,`sat_mt`,`sat_cr`,`sat_wr`,`act`,`act_en`,`act_mt`,`act_rd`,`act_sc`,`act_wr` FROM `students` WHERE `id` = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $student = getStudents(getResult($stmt))[0];
    $stmt->close();
  }
?>
    <div class="bg" onclick="closeHelp()"></div>

    <div class="container">

      <div class="popup info-popup">
        <a class="close" onclick="closeHelp()">&nbsp;</a>
        <div class="info-container">
          <h2>Information</h2>
          <?php require_once "util/data-info.php"; ?>
        </div>
      </div>

      <div class="starter-template">
        <?php if($result == NULL): ?>
          School not found. Please <a href="schools.php">go back</a> and try again.
        <?php else: ?>
          <h1><?php echo $result->name(); ?></h1>
          <div class="top">
            <div>
              Acceptance
              <canvas id="accept-chart" width="150" height="150"></canvas>
            </div>
            <div>
              Gender
              <canvas id="gender-chart" width="150" height="150"></canvas>
            </div>
          </div>
          <div class="left">
            <h2>Location</h2>
            <?php
              echo "<ul>";
              echo "<li>" . $result->address() . "</li>";
              echo "<li>" . $result->city() . ", " . $result->state() . " " . $result->zip() . "</li>";
              echo "</ul>";

              echo "<ul>";
              echo "<li>" . $result->county() . "</li>";
              echo "<li>" . $result->urbanization() . '<a class="help" onclick="help(\'setting\')"></a></li>';
              echo "<li>Congressional District: " . $result->congressionalDistrict() . "</li>";
              echo "</ul>";

              $lon = $result->longitude();
              $lat = $result->latitude();
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
              echo "<tr><td>Applicants:</td><td>" . $result->applicants() . '</td></tr>';
              echo "<tr><td>Admitted:</td><td>" . $result->admitted() . "</td></tr>";
              echo "<tr><td>Acceptance Rate:</td><td>" . $result->acceptanceRate() . "</td></tr>";
              echo "<tr><td>Full-Time Enrolled:</td><td>" . $result->fullTimeEnrolled() . "</td></tr>";
              echo "<tr><td>Part-Time Enrolled:</td><td>" . $result->partTimeEnrolled() . "</td></tr>";
              echo "<tr><td>Enroll Rate:</td><td>" . $result->enrollRate() . "</td></tr>";
              echo "<tr><td>Male:</td><td>" . $result->proportionMale() . "</td></tr>";
              echo "</table>";

              echo "<table>";
              echo "<tr><td>Male Applicants:</td><td>" . $result->maleApplicants() . "</td></tr>";
              echo "<tr><td>Admitted Males:</td><td>" . $result->admittedMales() . "</td></tr>";
              echo "<tr><td>Male Acceptance Rate:</td><td>" . $result->maleAcceptanceRate() . "</td></tr>";
              echo "<tr><td>Full-Time Enrolled Males:</td><td>" . $result->fullTimeEnrolledMales() . "</td></tr>";
              echo "<tr><td>Part-Time Enrolled Males:</td><td>" . $result->partTimeEnrolledMales() . "</td></tr>";
              echo "<tr><td>Male Enroll Rate:</td><td>" . $result->maleEnrollRate() . "</td></tr>";
              echo "</table>";

              echo "<table>";
              echo "<tr><td>Female Applicants:</td><td>" . $result->femaleApplicants() . "</td></tr>";
              echo "<tr><td>Admitted Females:</td><td>" . $result->admittedFemales() . "</td></tr>";
              echo "<tr><td>Female Acceptance Rate:</td><td>" . $result->femaleAcceptanceRate() . "</td></tr>";
              echo "<tr><td>Full-Time Enrolled Females:</td><td>" . $result->fullTimeEnrolledFemales() . "</td></tr>";
              echo "<tr><td>Part-Time Enrolled Females:</td><td>" . $result->partTimeEnrolledFemales() . "</td></tr>";
              echo "<tr><td>Female Enroll Rate:</td><td>" . $result->femaleEnrollRate() . "</td></tr>";
              echo "</table>";

              echo "<table>";
              echo "<tr><td>Applicants Submitting SAT Scores:</td><td>" . $result->numberSubmittingSAT() . " (" . $result->proportionSubmittingSAT() . ")</td></tr>";
              echo "<tr><td>Applicants Submitting ACT Scores:</td><td>" . $result->numberSubmittingACT() . " (" . $result->proportionSubmittingACT() . ")</td></tr>";
              echo "</table>";

              echo "<table>";
              $satColor = ($result->sat25() && $result->sat75() && $student->sat()) ? ($student->sat() < $result->sat25() ? '#900' : ($student->sat() > $result->sat75() ? '#090' : '#990')) : '#000';
              echo '<tr><td>SAT Range:</td><td><span style="color: ' . $satColor . '">' . $result->satRange() . '</span><a class="help" onclick="help(\'score-ranges\')"></a></td></tr>';
              echo "<tr><td>SAT Critical Reading:</td><td>" . $result->satReadingRange() . '<a class="help" onclick="help(\'score-ranges\')"></a></td></tr>';
              echo "<tr><td>SAT Math:</td><td>" . $result->satMathRange() . '<a class="help" onclick="help(\'score-ranges\')"></a></td></tr>';
              echo "<tr><td>SAT Writing:</td><td>" . $result->satWritingRange() . '<a class="help" onclick="help(\'score-ranges\')"></a></td></tr>';
              echo "<tr><td>ACT Range:</td><td>" . $result->actRange() . '<a class="help" onclick="help(\'score-ranges\')"></a></td></tr>';
              echo "<tr><td>ACT English:</td><td>" . $result->actEnglishRange() . '<a class="help" onclick="help(\'score-ranges\')"></a></td></tr>';
              echo "<tr><td>ACT Math:</td><td>" . $result->actMathRange() . '<a class="help" onclick="help(\'score-ranges\')"></a></td></tr>';
              echo "<tr><td>ACT Writing:</td><td>" . $result->actWritingRange() . '<a class="help" onclick="help(\'score-ranges\')"></a></td></tr>';
              echo "</table>";

              echo "<table>";
              echo "<tr><td>Disabled:</td><td>" . $result->proportionDisabled() . '<a class="help" onclick="help(\'disabled\')"></a></td></tr>';
              echo "<tr><td>Application Fee (undergrads):</td><td>" . $result->undergraduateApplicationFee() . "</td></tr>";
              echo "<tr><td>Application Fee (grads):</td><td>" . $result->graduateApplicationFee() . "</td></tr>";
              echo "<tr><td>Dormitory Capacity:</td><td>" . $result->dormCapacity() . "</td></tr>";
              echo "<tr><td>Cost of Room:</td><td>" . $result->roomCost() . "</td></tr>";
              echo "<tr><td>Cost of Board:</td><td>" . $result->boardCost() . "</td></tr>";
              echo "<tr><td>Cost of Both:</td><td>" . $result->totalCost() . "</td></tr>";
              echo "</table>";
            ?>
          </div>
          <div class="right">
            <h2>Facts</h2>
            <?php
              echo "<ul>";
              echo "<li>" . $result->level() . '<a class="help" onclick="help(\'level\')"></a></li>';
              echo "<li>" . $result->control() . "</li>";
              echo "<li>Highest Degree Offered: " . $result->maxDegree() . "</li>";
              echo "</ul>";

              echo "<ul>";
              echo "<li>" . ($result->historicallyBlack() ? "" : "Not ") . "Historically Black</li>";
              echo "<li>" . $result->hasHospital() . "</li>";
              echo "<li>" . ($result->tribal() ? "A" : "Not a") . ' Tribal College or University<a class="help" onclick="help(\'tribal\')"></a></li>';
              echo "<li>" . ($result->openToPublic() ? "" : "Not ") . "Open to the General Public</li>";
              echo "<li>" . $result->closed() . "</li>";
              echo "<li>" . ($result->landGrant() ? "A" : "Not a") . ' Land Grant University<a class="help" onclick="help(\'land-grant\')"></a></li>';
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
              echo '<a class="help" onclick="help(\'distance\')"></a></li>';
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
<?php
  $extraF = '<script type="text/javascript">';

  $accepted = $result['admitted'];
  $acceptTotal = $result['applied'];
  $denied = $acceptTotal - $accepted;
  //$extraF .= 'var acceptData = [{value: ' . $denied . ', color: "#F7464A", highlight: "#FF5A5E", label: "Denied: ' . $denied . ' (' . round(100 * $denied / $acceptTotal) . '%)"}, {value: ' . $accepted . ', color: "#46BFBD", highlight: "#5AD3D1", label: "Accepted: ' . $accepted . ' ()"}];';
  $extraF .= 'var acceptData = [{value: ' . $denied . ', color: "#F7464A", highlight: "#FF5A5E", label: "Denied"}, {value: ' . $accepted . ', color: "#46BFBD", highlight: "#5AD3D1", label: "Accepted"}];';
  
  $extraF .= 'var genderData = [{value: ' . $result['enroll_full_f'] . ', color: "#F7464A", highlight: "#FF5A5E", label: "Female"}, {value: ' . $result['enroll_full_m'] . ', color: "#46BFBD", highlight: "#5AD3D1", label: "Male"}];';
  $extraF .= '</script>';
  $extraF .= '<script src="js/school.js"></script><script src="js/Chart.min.js"></script><script src="js/charts.js"></script>';
  require_once "util/footer.php";
?>