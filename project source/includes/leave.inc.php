<?php session_start();
 require_once 'dbh.inc.php';
if (!isset($_POST["many-submit"]) && !isset($_POST["single-submit"])) {
    header("Location: ../stud_dashboard.php?");
    exit();
}
if (isset($_SESSION["regno"])) {
    $student_id = $_SESSION['student_id'];
    $applied_on = date("y-m-d");
    //echo ("TODO UPDATE IN DB");
    if(isset($_POST["many-submit"]))
    {
        $days = 1;
        $leave_type =$_POST["leave-type"];
        $from_date  = $_POST["from-date"];
        $to_date = $_POST["to-date"];
        $reason = $_POST["reason"];
        $from_session = $_POST["from-session"];
        $to_session = $_POST["to-session"];
        $sql = "INSERT INTO leave_application (student_id,leave_type,days,from_date,from_session,to_date,to_session,reason,applied_on) VALUES ('$student_id','$leave_type','$days','$from_date','$from_session','$to_date','$to_session','$reason','$applied_on')";

        if (mysqli_query($conn, $sql)) {
            echo "New record created successfully";
        }
        else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
          }
    }
    else{
        $days = 0;
        $leave_type =$_POST["leave-type"];
        $date = $_POST["date"];
        $reason = $_POST["reason"];
        $session = $_POST["session"];
        $sql = "INSERT INTO leave_application (student_id,leave_type,days,from_date,from_session,reason,to_date,applied_on) VALUES ('$student_id','$leave_type','$days','$date','$session','$reason','2000-01-01','$applied_on')";

        if (mysqli_query($conn, $sql)) {
            echo "New record created successfully";
        }
        else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);

          }
    }
    header("Location: ../stud_dashboard.php?leave=success");
} else {
    header("Location: ../index.php?log=0");
    exit();
}
