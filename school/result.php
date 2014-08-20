<?php

  if(!isset($_GET['name'])) {
    header("Location: schools.php");
    die();
  }

  $nameArr = str_split($_GET['name']);
  $nameArrLen = count($nameArr);
  $name = '';
  for($i = 0; $i < $nameArrLen; $i++) {
    if($nameArr[$i] == '-') {
      if($i + 1 < $nameArrLen && $nameArr[$i+1] == '-') {
        $name .= '-';
        $i++;
      } else {
        $name .= ' ';
      }
    } else {
      $name .= $nameArr[$i];
    }
  }
  echo $name;

  require_once "../util/util.php";
  require_once "../util/get-db.php";
  $stmt = $mysql->prepare("SELECT * FROM `schools`,`supplementary` WHERE `schools`.`id` = `supplementary`.`id` AND `schools`.`name` = ?");
  $stmt->bind_param("s", $name);
  $stmt->execute();
  $result = getResult($stmt);
  if(count($result) > 0) $result = getSchools($result)[0];
  $stmt->close();

  $page = 2;
  $mode = 0;
  $title = $result ? $result->name() : 'School not found';
  $extra = '<link href="../styles/school.css" rel="stylesheet" />';
  $urlPrefix = "../";
  require_once "../util/header.php";

  if($result && $loggedIn) {
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
          <?php require_once "../util/data-info.php"; ?>
        </div>
      </div>

      <div class="starter-template">
        <?php if($result == NULL): ?>
          School not found. Please <a href="../schools.php">go back</a> and try again.
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
            <div>
              SAT
              <canvas id="sat-chart" width="150" height="150"></canvas>
            </div>
            <div>
              ACT
              <canvas id="act-chart" width="150" height="150"></canvas>
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
              $satColor = $loggedIn ? getColor($result->hasSat25() && $result->hasSat75(), $result->sat25(), $result->sat75(), $student->hasSat(), $student->sat()) : '#000';
              echo '<tr><td>SAT Range:</td><td><span style="color: ' . $satColor . '">' . $result->satRange() . '</span><a class="help" onclick="help(\'score-ranges\')"></a></td></tr>';
              $satCrColor = $loggedIn ? getColor($result->hasSatReading(), $result->satReading25(), $result->satReading75(), $student->hasSatReading(), $student->satReading()) : '#000';
              echo '<tr><td>SAT Critical Reading:</td><td><span style="color: ' . $satCrColor . '">' . $result->satReadingRange() . '<a class="help" onclick="help(\'score-ranges\')"></a></td></tr>';
              $satMtColor = $loggedIn ? getColor($result->hasSatMath(), $result->satMath25(), $result->satMath75(), $student->hasSatMath(), $student->satMath()) : '#000';
              echo '<tr><td>SAT Math:</td><td><span style="color: ' . $satMtColor . '">' . $result->satMathRange() . '<a class="help" onclick="help(\'score-ranges\')"></a></td></tr>';
              $satWrColor = $loggedIn ? getColor($result->hasSatWriting(), $result->satWriting25(), $result->satWriting75(), $student->hasSatWriting(), $student->satWriting()) : '#000';
              echo '<tr><td>SAT Writing:</td><td><span style="color: ' . $satWrColor . '">' . $result->satWritingRange() . '<a class="help" onclick="help(\'score-ranges\')"></a></td></tr>';
              $actColor = $loggedIn ? getColor($result->hasAct25() && $result->hasAct75(), $result->act25(), $result->act75(), $student->hasAct(), $student->act()) : '#000';
              echo '<tr><td>ACT Range:</td><td><span style="color: ' . $actColor . '">' . $result->actRange() . '<a class="help" onclick="help(\'score-ranges\')"></a></td></tr>';
              $actEnColor = $loggedIn ? getColor($result->hasActEnglish(), $result->actEnglish25(), $result->actEnglish75(), $student->hasActEnglish(), $student->actEnglish()) : '#000';
              echo '<tr><td>ACT English:</td><td><span style="color: ' . $actEnColor . '">' . $result->actEnglishRange() . '<a class="help" onclick="help(\'score-ranges\')"></a></td></tr>';
              $actMtColor = $loggedIn ? getColor($result->hasActMath(), $result->actMath25(), $result->actMath75(), $student->hasActMath(), $student->actMath()) : '#000';
              echo '<tr><td>ACT Math:</td><td><span style="color: ' . $actMtColor . '">' . $result->actMathRange() . '<a class="help" onclick="help(\'score-ranges\')"></a></td></tr>';
              echo '<tr><td>ACT Writing:</td><td><span style="color: #000' . '">' . $result->actWritingRange() . '<a class="help" onclick="help(\'score-ranges\')"></a></td></tr>';
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
              echo "<li>" . $result->distanceStatement() . '<a class="help" onclick="help(\'distance\')"></a></li>';
              echo "</ul>";

              echo "<ul>";
              echo "<li>" . $result->boardStatement() . "</li>";
              echo "<li>" . $result->requiredToLiveOnCampus() . "</li>";
              echo "</ul>";
            ?>

            <h2>Contact</h2>
            <?php
              echo "<p>";
              echo "P: " . $result->phone() . "<br />";
              echo "F: " . $result->fax() . "<br />";
              echo "</p>";

              echo "<p>";
              echo $result->website() . "<br />";
              echo $result->admissionsURL() . "<br />";
              echo $result->financialAidURL() . "<br />";
              echo $result->netPriceURL() . "<br />";
              echo $result->applicationURL() . "<br />";
              echo "</p>";
            ?>
          </div>
        <?php endif; ?>
      </div>

    </div>
<?php
  $extraF = '';
  if($result) {
    $extraF .= '<script type="text/javascript">';

    $extraF .= 'var acceptData = [{value: ' . $result->denied() . ', color: "#F7464A", highlight: "#FF5A5E", label: "Denied"}, {value: ' . $result->admitted() . ', color: "#46BFBD", highlight: "#5AD3D1", label: "Accepted"}];';
    $extraF .= 'var genderData = [{value: ' . $result->enrolledFemales() . ', color: "#F7464A", highlight: "#FF5A5E", label: "Female"}, {value: ' . $result->enrolledMales() . ', color: "#46BFBD", highlight: "#5AD3D1", label: "Male"}];';
    
    $selfColors = 'fillColor: "rgba(220,220,220,0.2)", strokeColor: "rgba(220,220,220,1)", pointColor: "rgba(220,220,220,1)", pointStrokeColor: "#fff", pointHighlightFill: "#fff", pointHighlightStroke: "rgba(220,220,220,1)"';
    $schoolColors = 'fillColor: "rgba(151,187,205,0.2)", strokeColor: "rgba(151,187,205,1)", pointColor: "rgba(151,187,205,1)", pointStrokeColor: "#fff", pointHighlightFill: "#fff", pointHighlightStroke: "rgba(151,187,205,1)"';
    $extraF .= 'var satData = {labels: ["Math", "Reading", "Writing"], datasets: [';
    if($loggedIn && $student->hasSATSubscores()) $extraF .= '{label: "You", ' . $selfColors . ', data: [' . $student->satMath() . ', ' . $student->satReading() . ', ' . $student->satWriting() . ']}, ';
    $extraF .= '{label: "' . $result->name() . '", ' . $schoolColors . ', data: [' . $result->satMath50() . ', ' . $result->satReading50() . ', ' . $result->satWriting50() . ']}]};';
    $extraF .= 'var actData = {labels: ["Math", "English", "Writing"], datasets: [';
    if($loggedIn && $student->hasActMath() && $student->hasActEnglish() && $student->hasActWriting()) $extraF .= '{label: "You", ' . $selfColors . ', data: [' . $student->actMath() . ', ' . $student->actReading() . ', ' . $student->actWriting() . ']}, ';
    $extraF .= '{label: "' . $result->name() . '", ' . $schoolColors . ', data: [' . $result->actMath50() . ', ' . $result->actEnglish50() . ', ' . $result->actWriting50() . ']}]};';

    $extraF .= '</script>';
  }

  $extraF .= '<script src="../js/school.js"></script><script src="../js/Chart.min.js"></script><script src="../js/charts.js"></script>';
  require_once "../util/footer.php";
?>