<?php
  require_once "util/util.php";

  $error = 0;
  if(anySet('email', 'pass')) {
    if(!allSet('email', 'pass')) {
      $error = 1;
    } else {
      $email = $_POST['email'];
      $pass = $_POST['pass'];
      if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 2;
      } else {

        require_once "util/get-db.php";
        if(signIn($email, $pass)) {
          header("Location: dashboard.php");
          die();
        } else {
          $error = 3;
        }

      }
    }
  }

  $page = -1;
  $extra = '<link href="styles/signin.css" rel="stylesheet">';
  require_once "util/header.php";
?>

    <div class="container">

      <form class="form-signin" role="form" method="post" action="#">
        <h2 class="form-signin-heading">Please sign in</h2>
        <?php if($error == 1): ?>
          <span class="error">All fields are required.</span>
        <?php endif; ?>
        <?php if($error == 2): ?>
          <span class="error">You must enter a valid email address.</span>
        <?php endif; ?>
        <?php if($error == 3): ?>
          <span class="error">Incorrect email and/or password.</span>
        <?php endif; ?>
        <input type="email" class="form-control first" placeholder="Email address" required autofocus name="email" id="email"<?php e('email'); ?> />
        <input type="password" class="form-control last" placeholder="Password" required name="pass" id="pass"<?php e('pass'); ?> />
        <label class="checkbox">
          <input type="checkbox" value="remember-me" name="remember" id="remember"> Remember me
        </label>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
      </form>

    </div> <!-- /container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="js/jquery.min.js"></script>
  </body>
</html>
