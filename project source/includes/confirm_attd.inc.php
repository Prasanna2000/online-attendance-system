<?php session_start();
require_once 'dbh.inc.php';
require_once 'functions.inc.php';
if(!isset($_GET["sub"])) {
    if(isset($_SESSION["uid"])) {
    header("Location: ../".$_SESSION["dashboard"]);
    }
    else {
        header("Location: ../index.php?log=0");
    }
    exit();
}

$subject = $_GET["sub"];
$date = date("Y-m-d");
$day = date("w", strtotime($date));
$i =0;

if (isset($_POST["confirm"])) {
    $hours_confirm = $_POST["hours_confirm"];
    $hours_confirm = explode(",",$hours_confirm);
    $mark_all = $_POST["present"];
    $edit_confirm = $_POST["edit_confirm"];
    foreach($hours_confirm as $h)
    {
        echo $h,$date,$subject,$edit_confirm;
        $timetable_id_sql = "SELECT id FROM time_table where subject_id='$subject' and hour='$h' and day='$day';";
        $result = mysqli_query($conn, $timetable_id_sql);
        if ($row = mysqli_fetch_assoc($result)) {
            $t_id = $row["id"];
            echo $t_id;
            if (mysqli_num_rows($result) > 0) {
                if($edit_confirm==0){
                    $sql = "select * from student where class_id in(SELECT class_id from subject where id='$subject') and id not in(select student_id from attendance_master where date='$date' and time_table_id='$t_id');";
                    $result = mysqli_query($conn,$sql);
                    if(mysqli_num_rows($result)>0)
                    {
                        while($row = mysqli_fetch_assoc($result))
                        {
                            $studc_id = $row["id"];
                            $sql = "insert into attendance_master values($i,$studc_id,$t_id,'$date',0,1,$subject)";
                            if (!mysqli_query($conn, $sql)) {
                                echo mysqli_error($conn);
                                exit("SERVER ERROR!");
                            }
                        }
                    }
                }
                else{
                    $sql = "update attendance_master set confirm=1 where date='$date' and time_table_id='$t_id';";
                    if (!mysqli_query($conn, $sql)) {
                        echo mysqli_error($conn);
                        exit("SERVER ERROR!");
                    }
                    else{
                        echo $date,$t_id;
                    }
                }
            }
        }
    }
    $subcode = $_GET['subcode'];
    header("Location: ../subject.php?code=" . $subject."&attd=1&sub=$subcode");
} else if (isset($_POST["edit"])) {
    $edit = $_POST["edit"];
    $hours = $_POST["hours"];
    $hours = explode(",",$hours);
    $reg_no = $_POST["id"];
    $attd = $_POST["status"];   
    //sql 
    foreach($hours as $h)
    {
        $timetable_id_sql = "SELECT id FROM time_table where subject_id='$subject' and hour='$h' and day='$day';";
        $result = mysqli_query($conn, $timetable_id_sql);
        if(mysqli_num_rows($result) > 0){
            if ($row = mysqli_fetch_assoc($result)) {
            $t_id = $row["id"];
                $student_id = "SELECT id FROM student where register_number='$reg_no';";
                $res_regno = mysqli_query($conn, $student_id);
                if (mysqli_num_rows($res_regno) > 0) {
                if ($row = mysqli_fetch_assoc($res_regno)) {
                    $stud_id = $row["id"];
                    
                        if($edit==0)
                        {
                            $sql1 ="delete from attendance_master where student_id='$stud_id' and time_table_id='$t_id' and date='$date';";
                            if (!mysqli_query($conn, $sql1)) {
                                echo mysqli_error($conn);
                                exit("SERVER ERROR!");
                            }
                            //delete TODO (if they are gona directly toggle again and again we have to delete the prev attendance on that hour day and stud_id)
                            $sql = "insert into attendance_master values($i,$stud_id,$t_id,'$date',$attd,1,$subject)";
                            if (!mysqli_query($conn, $sql)) {
                                echo mysqli_error($conn);
                                exit("SERVER ERROR!");
                            }
                        }
                        else{
                            $sql = "update attendance_master set status='$attd' where student_id='$stud_id' and time_table_id='$t_id' and date='$date';";
                            if (!mysqli_query($conn, $sql)) {
                                echo mysqli_error($conn);
                                exit("SERVER ERROR!");
                            }
                        }
                    }
                }
            }
        }
    }
    exit("1");
} else {
    header("Location: ../".$_SESSION["dashboard"]);
    exit();
}
