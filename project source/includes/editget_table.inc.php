<?php
require_once 'dbh.inc.php';
$hour = $_POST["hour"];
$subject_id = $_POST["subject_id"];
$reg_no = $_POST["regno"];
$date = $_POST["date"];
$day =date("w",strtotime($date));
$status = $_POST["tostatus"];

//echo $hour,$subject_id,$reg_no,$date,$day,$status,"<br>";

$sql = "SELECT id from time_table where day='$day' and subject_id='$subject_id' and hour='$hour';";
$result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        if ($row = mysqli_fetch_assoc($result)) {
            $t_id = $row["id"];
            $sql_stud_id = "select id from student where register_number='$reg_no';";
            $stud_id_res = mysqli_query($conn, $sql_stud_id);
            if(mysqli_num_rows($stud_id_res) > 0) {
                if ($row1 = mysqli_fetch_assoc($stud_id_res)) {
                    $s_id = $row1["id"];
                    $sql_update = "update attendance_master set status='$status' where date='$date' and time_table_id='$t_id' and student_id='$s_id';";
                    if(mysqli_query($conn,$sql_update))
                    {
                        echo "1";
                        exit();
                    }
                    else{
                        echo mysqli_error($conn);
                        exit(0);
                    }
                }
                else{
                    echo mysqli_error($conn);
                    exit(0);
                }
            }
            else{
                echo mysqli_error($conn);
                exit(0);
            }
        }else{
            echo mysqli_error($conn);
            exit(0);
        }
    }else{
        echo mysqli_error($conn);
        exit(0);
    }
    exit(1);