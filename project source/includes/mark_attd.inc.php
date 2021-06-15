<?php
require_once 'dbh.inc.php';
if (!isset($_GET["sub"]) || !isset($_POST["hour"])) {
    header("Location: ../index.php?");
}
$subject = $_GET["sub"];
$hour = $_POST["hour"];
$students = $_POST["students"];
$attd_type = strcmp($_POST["attd_type"], "Present") == 0 ? 1 : 0;
sort($hour);
$allStudents_sql = "SELECT * FROM student where class_id IN(select class_id from subject where id='$subject');";
$all_students = array();
$ans = mysqli_query($conn, $allStudents_sql);
if (mysqli_num_rows($ans) > 0) {
    while ($row = mysqli_fetch_assoc($ans)) {
       // array_push($all_students, $row["id"]);
       $all_students+=[$row["id"]=>$row["register_number"]];
    }
} else {
    return null;
}
//$date=date("Y-m-d");
$date = date("Y-m-d");
$day = date("w", strtotime($date));
$t="";

foreach ($hour as $h) {
    $i=0;
    $timetable_id_sql = "SELECT id FROM time_table where subject_id='$subject' and hour='$h' and day='$day';";
   // $timetable_id_sql = "SELECT id FROM time_table where subject_id='$subject'";
    $result = mysqli_query($conn, $timetable_id_sql);
    if ($row = mysqli_fetch_assoc($result)) {
        $t_id = $row["id"];
        $t=$t_id;
        if (mysqli_num_rows($result) > 0) {
            foreach ($all_students as $stud_id=>$stud) {
                if (in_array($stud, $students)) {
                    $sql = "insert into attendance_master values($i,$stud_id,$t_id,'$date',$attd_type,0,$subject)";
                    if (!mysqli_query($conn, $sql)) {
                        echo mysqli_error($conn);
                        exit("SERVER ERROR!");
                    }
                } else {
                    $att = 1 - $attd_type;
                    $sql = "insert into attendance_master values($i,$stud_id,$t_id,'$date',$att,0,$subject)";
                    if (!mysqli_query($conn, $sql)) {
                        echo mysqli_error($conn);
                        exit("SERVER ERROR!");
                    }
                }
            }
        }
    }
    $i+=1;
}


header("Location: ../attendance.php?sub=".$subject."&h=".implode(",",$hour)."&t=$t&subcode=".$_GET["subcode"]);
//RETURN SUB and HOUR as a string with separated commas