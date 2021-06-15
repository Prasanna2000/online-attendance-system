<?php session_start();
require_once 'dbh.inc.php';
if(!isset($_POST["id"])) {
    if(isset($_SESSION["uid"])) {
    header("Location: ../".$_SESSION["dashboard"]);
    }
    else {
        header("Location: ../index.php?log=1");
    }
    exit();
}
$leave_id = $_POST["id"];
//TODO ALL APPROVAL STUFF
$sql = "update leave_application set status=1 where id='$leave_id';";
if(mysqli_query($conn,$sql))
{
    echo "1";
}
else{
    echo mysqli_error($conn);
}
exit();

header("Location: ../approve_leave.php?approve=0");