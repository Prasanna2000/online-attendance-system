<?php session_start();
if(isset($_SESSION["uid"]) or isset($_SESSION["regno"]))
{
    session_destroy();
    header("Location: ../index.php");
}
else {
    header("Location: ../index.php?log=1");
}

