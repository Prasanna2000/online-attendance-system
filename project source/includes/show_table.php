<?php

require_once 'dbh.inc.php';
    $date = $_POST["date"];
    $day = date("w", strtotime($date));
    $subject_id = $_POST["subject_id"];
    $hour = $_POST["hour"];

    $tt_sql = "select id from time_table where day='$day' and subject_id='$subject_id' and hour='$hour';";
    $result1 = mysqli_query($conn,$tt_sql);
    if(mysqli_num_rows($result1)>0)
    {
        if($row = mysqli_fetch_assoc($result1))
        {
            $tt_id = $row["id"];
            $sql = "SELECT student.name,student.register_number, attendance_master.status
            FROM attendance_master
            JOIN student
            ON student.id=attendance_master.student_id WHERE date='$date' and time_table_id='$tt_id';";

            $result = mysqli_query($conn,$sql);
            $name = array();
            $reg_num = array();
            $attendance = array();
            if(mysqli_num_rows($result)>0)
            {
                while($row = mysqli_fetch_assoc($result))
                {
                    array_push($name,$row["name"]);
                    array_push($reg_num,$row["register_number"]);
                    array_push($attendance,$row["status"]);
                }
                $name = implode(",",$name);
                $attendance = implode(",",$attendance);
                $reg_num = implode(",",$reg_num);
            // echo $result_string;
                
                $result_array = array("name"=>json_encode($name),"reg_num"=>json_encode($reg_num),"attendance"=>json_encode($attendance));
                exit(json_encode($result_array));
            }
        }
    }
    