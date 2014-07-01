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
  $id = authenticate();
  if($id < 0) { // redirect user if they're not logged in
    header("Location: sign-in.php");
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
          <a class="navbar-brand" href="dashboard.php">CollegeFinder</a>
        </div>
        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li<?php c(0); ?>><a href="dashboard.php">Home</a></li>
            <li<?php c(1); ?>><a href="info.php">Info</a></li>
            <li<?php c(2); ?>><a href="schools.php">Schools</a></li>
            <li<?php c(3); ?>><a href="visiting.php">Plan a Visit</a></li>
          </ul>
          <form class="navbar-right navbar-form" role="form" action="sign-out.php" method="post">
            <button type="submit" class="btn btn-success">Sign Out</button>
          </form>
        </div><!--/.navbar-collapse -->
      </div>
    </div>