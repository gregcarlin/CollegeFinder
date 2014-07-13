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
            <img src="images/search.png" alt="Example list of schools." id="search" />
          </li>

          <li>
            You control what you see.
            <p>You specify exactly what kinds of schools you want to see. Select by major, test scores, location, and more.</p>
            <img src="images/degree.png" alt="Example specifying degree." id="degree" />
          </li>

          <li>
            We help you determine where to apply.
            <p>We guide you through the whole process, from creating initial lists to visiting. <a href="sign-up.php">Sign up</a> now to start your application!</p>
            <!-- TODO: add a picture here -->
          </li>
        </ol>
      </div>

    </div><!-- /.container -->
<?php
  require_once "util/footer.php";
?>