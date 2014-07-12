<?php
  if(!isset($bootstrap)) $bootstrap = true;
  if(!isset($jquery)) $jquery = true;
?>

    </div>
    
    <?php if($jquery) echo '<script src="js/jquery.min.js"></script>'; ?>
    <?php if($bootstrap) echo '<script src="bootstrap/js/bootstrap.min.js"></script>'; ?>

    <div class="footer">
      <ul>
        <li><a href="attributions.php">Attributions</a></li>
        <li><a href="">Other Thing</a></li>
        <li><a href="">Other Thing</a></li>
      </ul>
    </div>
  </body>
</html>