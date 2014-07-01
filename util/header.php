<?php
  if(!isset($page)) {
    header("HTTP/1.0 404 Not Found");
    die();
  }

  function c($n) {
    global $page;
    if($page == $n) {
      echo ' class="active"';
    }
  }

  require_once "util/util.php";
  require_once "util/get-db.php";
  if(authenticate() >= 0) { // redirect user if they're logged in
    header("Location: dashboard.php");
    die();
  }
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="" />
    <link rel="shortcut icon" href="favicon.ico" />

    <title>CollegeFinder</title>

    <!-- Bootstrap core CSS -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" />

    <!-- Custom styles for this template -->
    <link href="styles/all.css" rel="stylesheet" />

    <?php if(isset($extra)) echo $extra; ?>

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    <script type="text/javascript">
      function signUp() {
        $(".sign-up-pop").toggle();
      }
    </script>
  </head>

  <body>

    <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="index.php">CollegeFinder</a>
        </div>
        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li<?php c(0); ?>><a href="index.php">Home</a></li>
            <li<?php c(1); ?>><a href="details.php">About</a></li>
            <li<?php c(2); ?>><a href="#contact">Contact</a></li>
          </ul>
          <form class="navbar-right navbar-form" role="form" action="sign-in.php" method="post">
            <?php if($page >= 0): ?>
              <div class="form-group">
                <input type="text" placeholder="Email" class="form-control" name="email" id="email">
              </div>
              <div class="form-group">
                <input type="password" placeholder="Password" class="form-control" name="pass" id="pass">
              </div>
              <button type="submit" class="btn btn-success">Sign in</button>
            <?php endif; ?>
            <?php if($page != -2): ?>
              <button type="button" class="btn btn-success" onclick="signUp()">Sign up</button>
            <?php endif; ?>
          </form>
        </div><!--/.navbar-collapse -->
      </div>
    </div>
    <div class="popup sign-up-pop">
      <h2>Sign Up</h2>
      <form role="form" action="sign-up.php" method="post">
        <table>
          <tr>
            <td><label for="fname">First Name:</label></td>
            <td><input type="text" id="fname" name="fname" class="form-control" /></td>
          </tr>
          <tr>
            <td><label for="lname">Last Name:</label></td>
            <td><input type="text" id="lname" name="lname" class="form-control" /></td>
          </tr>
          <tr>
            <td><label for="email">Email:</label></td>
            <td><input type="text" id="email" name="email" class="form-control" /></td>
          </tr>
          <tr>
            <td><label for="pass">Password:</label></td>
            <td><input type="password" id="pass" name="pass" class="form-control" /></td>
          </tr>
          <tr>
            <td colspan="2"><input type="submit" value="Begin" class="btn btn-primary" /></td>
          </tr>
        </table>
      </form>
    </div>