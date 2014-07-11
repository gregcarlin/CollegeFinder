<?php
  require_once "util/util.php";

  $error = 0;
  if(anySet("fname", "lname", "email", "pass")) {
    if(!allSet('fname', 'lname', 'email', 'pass')) {
      $error = 1;
    } else {
      $fname = $_POST['fname'];
      $lname = $_POST['lname'];
      $email = $_POST['email'];
      $pass = $_POST['pass'];
      if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 2;
      } else {
        
        require_once "util/get-db.php";
        $stmt = $mysql->prepare("SELECT * FROM `students` WHERE `email` = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        if($stmt->fetch()) {
          $error = 3;
          $stmt->close();
        } else {

          $stmt->close();

          $stmt = $mysql->prepare("INSERT INTO `students` VALUES(NULL, ?, ?, ?, AES_ENCRYPT(?, 'supersecretkey'), NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL)");
          $stmt->bind_param("ssss", $fname, $lname, $email, $pass);
          $stmt->execute();
          $stmt->close();

          assert(signIn($email, $pass));

          header("Location: dashboard.php");
          die();

        }

      }
    }
  }

  $page = -2;
  $mode = 2;
  $title = 'Sign Up';
  $extra = '<link href="styles/signin.css" rel="stylesheet">';
  require_once "util/header.php";
?>

    <div class="container">

      <form class="form-signin" role="form" method="post" action="#">
        <h2 class="form-signin-heading">Sign up</h2>
        <?php if($error == 1): ?>
          <span class="error">All fields are required.</span>
        <?php endif; ?>
        <input type="text" class="form-control first" placeholder="First Name" required autofocus name="fname" id="fname"<?php e('fname'); ?> />
        <input type="text" class="form-control" placeholder="Last Name" required name="lname" id="lname"<?php e('lname'); ?> />
        <?php if($error == 2): ?>
          <input type="text" class="form-control error" disabled value="You must enter a valid email address." />
        <?php endif; ?>
        <?php if($error == 3): ?>
          <input type="text" class="form-control error" disabled value="An account with this email already exists." />
        <?php endif; ?>
        <input type="email" class="form-control" placeholder="Email address" required name="email" id="email"<?php e('email'); ?> />
        <input type="password" class="form-control last" placeholder="Password" required name="pass" id="pass"<?php e('pass'); ?> />
        <button class="btn btn-lg btn-primary btn-block" type="submit">Sign up</button>
      </form>

    </div> <!-- /container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="js/jquery.min.js"></script>
  </body>
</html>
