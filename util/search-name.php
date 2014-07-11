<?php
  if(!isset($_POST['name']) || preg_match('/[^a-z_\-0-9 ]/i', $_POST['name'])) {
    header("HTTP/1.0 404 Not Found");
    die();
  }

  $name = strtolower($_POST['name']);
  $stmt = $mysql->prepare("SELECT * FROM `schools`,`supplementary` WHERE `schools`.`id` = `supplementary`.`id` AND (LOWER(`schools`.`name`) LIKE '%" . $name . "%' OR LOWER(`schools`.`alias` LIKE '%" . $name . "%')) LIMIT 0,101");
  $stmt->execute();
  $schools = getResult($stmt);
  $stmt->close();
  $count = count($schools);
?>
    <div class="container">

      <div class="starter-template">
        <h1>View selected schools</h1>

        <?php
          if($count > 0 && $count <= 100) {
            echo '<div class="result-count">' . $count . ' results found.</div>';
          }
        ?>
        <table class="results">
          <?php if($count > 100): ?>
            <tr>
              <td>More than 100 results were found. Please narrow your search.</td>
            </tr>
          <?php elseif($count > 0): ?>
            <tr>
              <th>Name</th>
              <th>City</th>
              <th>State</th>
              <th>SAT Range</th>
              <th>ACT Range</th>
              <th>Acceptance</th>
              <th>List</th>
            </tr>
            <?php
              foreach($schools as $school) {
                echo formatSchool($school);
              }
            ?>
          <?php else: ?>
            <tr>
              <td>No results found for the term '<?php echo $_POST['name']; ?>'.</td>
            </tr>
          <?php endif; ?>
        </table>
      </div>

    </div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="js/jquery.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <script src="js/schools.js"></script>
  </body>
</html>