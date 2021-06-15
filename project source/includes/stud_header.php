<?php session_start(); 
require_once 'dbh.inc.php';
require_once 'functions.inc.php';?>
<!DOCTYPE html>
<html>

<head>
  <title>Online Attendance Portal</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" href="css/dashboard.css" />
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  <script src="js/plotly.min.js"></script>

  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>


  <style>
    body {
      box-sizing: border-box;
      background-color: gainsboro;
    }
  </style>
</head>

<body>
  <div id="mySidenav" class="sidenav">
    <a id="closebtn" href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
    <a href="stud_dashboard.php">Dashboard</a>
    <a href="apply_leave.php">Apply for Leave</a>
    <a href="timetable.php">View Timetable</a>

  </div>

  <div class="navbar-static-top" id="main">
    <ul class="ulnav">
      <li class="linav" id="ver-nav"><span onclick="openNav()">&#9776;</span></li>
      <li class="linav"><a href="stud_dashboard.php">Attendance Portal</a></li>
      <li class="linav" id=""><?php echo $_SESSION["name"]."hello"?></li>
      <li class="linav" id="logout"><a href="includes/logout.inc.php">Logout</a></li>
    </ul>
  </div>
  <br>