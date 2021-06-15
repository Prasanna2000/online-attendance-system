<?php session_start(); ?>
<!DOCTYPE html>
<html>

<head>
  <title>Login</title>
  <link rel="stylesheet" href="css/login_style.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>


</head>

<body>
  <div class="wrapper fadeInDown" style="background-color:#87d1fc">

    <?php if (isset($_SESSION["uid"])) {
      header("Location: ".$_SESSION["dashboard"]."?log=1");
    } else if (isset($_SESSION["uid"])) {
      header("Location: stud_dashboard.php");
    } ?>

    <!-- Warning Messages -->
    <?php
    if (isset($_GET["log"])) {
    ?>
      <center>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
          Please login first!
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
      </center>
      <?php
    }
    if (isset($_GET["error"])) {
      if ($_GET["error"] == "stmtfailed") {
      ?>
        <center>
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            Something went wrong! Please try again later!
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        </center>

      <?php
      } else if ($_GET["error"] == "wrongpwd") { ?>
        <center>
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            Incorrect Password! Please Try Again!
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        </center>
      <?php } else if ($_GET["error"] == "invaliduname") { ?>
        <center>
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            Invalid Username! Please Try Again!
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        </center>
      <?php
      } else if ($_GET["error"] == "invalidstud") { ?>
        <center>
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            Invalid Register number or Date of birth!
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        </center>

    <?php
      }
    }

    ?>

    <div id="formContent">
      <div>
        <img src="img/psgitech.png" alt="itech logo" width="30%" style="margin-top: 5%;">
      </div>
      <!-- Login Form -->
      <?php if (isset($_POST["faculty-toggle"])) {
      ?>
        <form id="faculty_login" style="padding-top: 30px" method="POST" action="includes/login.inc.php">
          <input type="text" id="login" class="fadeIn second" name="uname" placeholder="email id" required>
          <input type="password" id="password" class="fadeIn third" name="passwd" placeholder="password" required>
          <input type="submit" class="fadeIn fourth" style="margin-top:10%" name="faculty" value="Log In">
        </form>
        <form action="" method="POST"><input type="submit" value="Student Login"></form>
      <?php } else { ?>
        <form id="stud_login" style="padding-top: 30px;" method="POST" action="includes/login.inc.php">
          <input type="text" id="regno" class="fadeIn second" name="regno" placeholder="Register Number" required>
          <br><br>Date of Birth
          <input type="date" id="dob" class="fadeIn third" name="dob" placeholder="Date of Birth" required>
          <input type="submit" class="fadeIn fourth" style="margin-top:10%" name="student" value="Log In">
        </form>
        <form action="" method="POST"><input type="submit" name="faculty-toggle" value="Faculty Login"></form>
      <?php } ?>
      <!-- Forgot Passowrd -->

      <!-- <div id="formFooter">
                <a class="underlineHover" href="#">Forgot Password?</a>
              </div> -->

    </div>
  </div>
</body>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

</html>