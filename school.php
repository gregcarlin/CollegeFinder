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
  var_dump($result);
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
              echo $result["county"];
              echo "</p>";

              //echo "<p>";
              $lon = $result["longitude"];
              $lat = $result["latitude"];
              $lon = $lon < 0 ? (-$lon . "&#176; W") : ($lon . "&#176; E");
              $lat = $lat < 0 ? (-$lat . "&#176; S") : ($lat . "&#176; N");
              $coord = $lat . " " . $lon;
              //echo $coord;
              //echo "</p>";

              if($lon != 0 || $lat != 0) echo '<iframe width="300" height="300" frameborder="0" style="border:0" src="https://www.google.com/maps/embed/v1/place?key=AIzaSyADx_CXHX0I2ezcwRsGboW2X3Diucufi7w&q=' . $coord . '"></iframe>';
            ?>
          </div>
          <div class="mid">
            <h2>Statistics</h2>
          </div>
          <div class="right">
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