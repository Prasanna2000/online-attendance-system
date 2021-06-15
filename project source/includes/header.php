<?php session_start(); 
require_once 'includes/dbh.inc.php';
require_once 'includes/functions.inc.php';
//$images = array("img/b1.png","img/b2.png","img/b3.png","img/b4.png","img/b5.png","img/b6.png");
$images = array("img/1.jpg","img/7.jpg","img/5.JPG","img/6.JPG");?>
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

  <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons" />

  <style>
    body {
      box-sizing: border-box;
      background-color: gainsboro;
    }
  </style>
</head>
<?php
if ($_SESSION["staff"]["role"] == 0)
  $dashboard = "dashboard.php";
else if ($_SESSION["staff"]["role"] == 1)
  $dashboard = "tutor_dashboard.php";
else if ($_SESSION["staff"]["role"] == 2)
  $dashboard = "hod_dashboard2.php";
else if ($_SESSION["staff"]["role"] == 3)
  $dashboard = "principal_dashboard.php";
$_SESSION["dashboard"] = $dashboard;
?>

<body>
  <div id="mySidenav" class="sidenav">
    <a id="closebtn" href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
    <a href="<?php echo $dashboard;?>">Dashboard</a>
    <a href="#">Profile</a>

  </div>

  <div class="navbar-static-top" id="main">
    <ul class="ulnav">
      <li class="linav" id="ver-nav"><span onclick="openNav()">&#9776;</span></li>
      <li class="linav"><a href="<?php echo $dashboard;?>">Attendance Portal</a></li>
      <li class="linav" id="logout"><a href="includes/logout.inc.php">Logout</a></li>
    </ul>
  </div>