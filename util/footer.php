<?php
  if(!isset($urlPrefix)) die();
  if(!isset($bootstrap)) $bootstrap = true;
  if(!isset($jquery)) $jquery = true;
?>

    </div>
    
    <?php if($jquery) echo '<script src="' . $urlPrefix . 'js/jquery.min.js"></script>'; ?>
    <?php if($bootstrap) echo '<script src="' . $urlPrefix . 'bootstrap/js/bootstrap.min.js"></script>'; ?>
    <?php if(isset($extraF)) echo $extraF; ?>

    <div class="footer">
      <ul>
        <li><a href="<?php echo $urlPrefix; ?>attributions.php">Attributions</a></li>
        <li><a href="">Other Thing</a></li>
        <li><a href="">Other Thing</a></li>
      </ul>
    </div>
  </body>
</html>