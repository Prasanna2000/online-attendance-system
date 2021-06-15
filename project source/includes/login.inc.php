<?php session_start();

if (isset($_POST['faculty'])) {

    $name = $_POST['uname'];
    $passwd = $_POST['passwd'];

    require_once 'dbh.inc.php';
    require_once 'functions.inc.php';

    loginFaculty($conn, $name, $passwd);
} else if (isset($_POST['student'])) {
    $regno = $_POST['regno'];
    $dob = $_POST['dob'];

    require_once 'dbh.inc.php';
    require_once 'functions.inc.php';

    loginStudent($conn, $regno, $dob);
} else {
    header("Location: ../login.php");
    exit();
}
