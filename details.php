<?php
  $page = 1;
  $mode = 2;
  $title = 'About';
  $extra = '<link href="styles/details.css" rel="stylesheet" />';
  require_once "util/header.php";
?>

    <div class="container">

      <div class="starter-template">
        <h1>How it works</h1>

        <ol class="lead">
          <li>
            We do the leg work.
            <p>We collect and organize data on thousands of schools across the country.</p>
            <div class="cover">
              <img src="images/search.png" /><!-- TODO make image fade away -->
            </div>
          </li>

          <li>
            You control what you see.
            <p>You specify exactly what kinds of schools you want to see. Select by major, test scores, location, and more.</p>
          </li>

          <li>
            We help you determine where to apply.
            <p>We guide you through the whole process, from creating initial lists to visiting.</p>
          </li>
        </ol>
      </div>

    </div><!-- /.container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>-->
    <script src="js/jquery.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <script src="js/details.js"></script>
  </body>
</html>
