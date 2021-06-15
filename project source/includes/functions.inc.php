<?php
function loginFaculty($conn, $name, $passwd)
{
    //Prepared Statements for fetching data from DB
    $sql = "SELECT * from users WHERE email = ?;";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: ../index.php?error=stmtfailed");
        exit();
    }
    mysqli_stmt_bind_param($stmt, "s", $name);
    mysqli_stmt_execute($stmt);

    $results = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($results)) {
        // $pwd_hashed = $row["password"];

        // $check_pwd = password_verify($passwd, $pwd_hashed);
        $pwd = $row["password"];

        if ($pwd === $passwd) {
            session_start();
            $_SESSION["uid"] = $row["id"];
            $_SESSION["uname"] = $row["email"];
            $_SESSION["dept_id"] = $row["dept_id"];
            $_SESSION["staff"] = $row;

            if ($row["role"] == 0) {
                header("Location: ../dashboard.php");
            } else if ($row["role"] == 1) {
                header("Location: ../tutor_dashboard.php");
            } else if ($row["role"] == 2) {
                header("Location: ../hod_dashboard2.php");
            } else if ($row["role"] == 3) {
                header("Location: ../principal_dashboard.php");
            } else {
                echo "SERVER ERROR!";
            }
            exit();
        } else {
            header("Location: ../index.php?error=wrongpwd");
            exit();
        }
    } else {
        header("Location: ../index.php?error=invaliduname");
        exit();
    }

    mysqli_stmt_close($stmt);
}

function loginStudent($conn, $regno, $dob)
{
    //Prepared Statements for fetching data from DB
    $sql = "SELECT * from student WHERE register_number = ? AND dob=?;";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: ../index.php?error=stmtfailed");
        exit();
    }
    mysqli_stmt_bind_param($stmt, "ss", $regno, $dob);
    mysqli_stmt_execute($stmt);

    $results = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($results)) {
        session_start();
        $_SESSION["regno"] = $row["register_number"];
        $_SESSION["name"] = $row["name"];
        $_SESSION["class_id"] = $row["class_id"];
        $_SESSION["student_id"] = $row["id"];
        header("Location: ../stud_dashboard.php");
        exit();
    } else {
        header("Location: ../index.php?error=invalidstud");
        exit();
    }
    mysqli_stmt_close($stmt);
}

function getTimeTable($conn)
{
    $class_id = $_SESSION["class_id"];
    $sql = "SELECT * FROM time_table WHERE subject_id IN (SELECT id FROM subject WHERE class_id = '$class_id');";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        return $result;
    } else {
        return null;
    }
}

function getappliedLeave($conn)
{
    $student_id = $_SESSION["student_id"];
    $sql = "SELECT * FROM leave_application WHERE student_id = '$student_id';";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        return $result;
    } else {
        return null;
    }
}

function getSubjectWiseTotalAttendance($conn) 
{
    $student_id = $_SESSION["student_id"];
    $sql_total = "SELECT subject_id, COUNT(*) FROM attendance_master WHERE student_id = '$student_id' GROUP BY subject_id; ";
    $sql_present = "SELECT subject_id, COUNT(*) FROM attendance_master WHERE student_id = '$student_id' and status='1' GROUP BY subject_id; ";

    $sql_subject = "SELECT name,id  FROM subject WHERE id IN (SELECT DISTINCT subject_id FROM attendance_master WHERE student_id = '$student_id');";

    $subject = array();

    $ans = mysqli_query($conn, $sql_subject);
    echo mysqli_error($conn);
    if (mysqli_num_rows($ans) > 0) {
        while ($row = mysqli_fetch_assoc($ans)) {
            $subject += [$row["id"] => array($row["name"])];
        }
    } else {
        return null;
    }
    $result_total = mysqli_query($conn, $sql_total);
    if (mysqli_num_rows($result_total) > 0) {
        while ($row = mysqli_fetch_assoc($result_total)) {
            array_push($subject[$row["subject_id"]], $row["COUNT(*)"]);
        }
    } else {
        return null;
    }

    $result_present = mysqli_query($conn, $sql_present);
    if (mysqli_num_rows($result_present) > 0) {
        while ($row = mysqli_fetch_assoc($result_present)) {
            array_push($subject[$row["subject_id"]], $row["COUNT(*)"]);
        }
    } else {
        return null;
    }
    return $subject;
}

function getTotalAttendance($conn) //for student 
{
    $student_id = $_SESSION["student_id"];
    $sql1 = "SELECT COUNT(*) FROM attendance_master WHERE student_id = '$student_id';";
    $sql2 = "SELECT COUNT(*) FROM attendance_master WHERE student_id = '$student_id' and status=1;";
    $res = array(0, 0);
    $result1 = mysqli_query($conn, $sql1);
    if (mysqli_num_rows($result1) > 0) {
        if ($row = mysqli_fetch_assoc($result1)) {
            $res[0] = $row["COUNT(*)"];
        }
    } else {
        return null;
    }
    $result2 = mysqli_query($conn, $sql2);
    if (mysqli_num_rows($result2) > 0) {
        if ($row = mysqli_fetch_assoc($result2)) {
            $res[1] = $row["COUNT(*)"];
        }
    } else {
        return null;
    }
    return $res;
}

function getSubjectWiseTotalClassAttendanceHod($conn,$year) // for hod subjectwise
{
    $user_id = $_SESSION["uid"];
    $sql_total = "SELECT subject_id,COUNT(*) FROM attendance_master where student_id IN(select id FROM student WHERE class_id IN (SELECT id FROM class WHERE dept_id IN (select dept_id FROM users where id='$user_id') AND year='$year')) GROUP BY subject_id;";
    $sql_present = "SELECT subject_id,COUNT(*) FROM attendance_master where student_id IN(select id FROM student WHERE class_id IN (SELECT id FROM class WHERE dept_id IN (select dept_id FROM users where id='$user_id')AND year='$year')) AND status='1' GROUP BY subject_id;";
    $sql_subject = "SELECT name,id FROM subject where class_id IN (SELECT id FROM class WHERE dept_id IN (select dept_id FROM users where id='$user_id')AND year='$year');";
    $subject = array();

    $ans = mysqli_query($conn, $sql_subject);
    echo mysqli_error($conn);
    if (mysqli_num_rows($ans) > 0) {
        while ($row = mysqli_fetch_assoc($ans)) {
            $subject += [$row["id"] => array($row["name"])];
        }
    } else {
        return null;
    }
    $result_total = mysqli_query($conn, $sql_total);
    if (mysqli_num_rows($result_total) > 0) {
        while ($row = mysqli_fetch_assoc($result_total)) {
            array_push($subject[$row["subject_id"]], $row["COUNT(*)"]);
        }
    } else {
        return null;
    }

    $result_present = mysqli_query($conn, $sql_present);
    if (mysqli_num_rows($result_present) > 0) {
        while ($row = mysqli_fetch_assoc($result_present)) {
            array_push($subject[$row["subject_id"]], $row["COUNT(*)"]);
        }
    } else {
        return null;
    }
    return $subject;
}


function getSubjectWiseTotalClassAttendance($conn) // for tutor
{
    $user_id = $_SESSION["uid"];

    $sql_total = "SELECT subject_id,COUNT(*) FROM attendance_master where student_id IN(select id FROM student WHERE class_id IN (SELECT id FROM class WHERE tutor_id='$user_id')) GROUP BY subject_id;";
    $sql_present = "SELECT subject_id,COUNT(*) FROM attendance_master where student_id IN(select id FROM student WHERE class_id IN (SELECT id FROM class WHERE tutor_id='$user_id')) AND status='1' GROUP BY subject_id;";

    $sql_subject = "SELECT name,id FROM subject where class_id IN (SELECT id FROM class WHERE tutor_id='8');";

    $subject = array();

    $ans = mysqli_query($conn, $sql_subject);
    echo mysqli_error($conn);
    if (mysqli_num_rows($ans) > 0) {
        while ($row = mysqli_fetch_assoc($ans)) {
            $subject += [$row["id"] => array($row["name"])];
        }
    } else {
        return null;
    }
    $result_total = mysqli_query($conn, $sql_total);
    if (mysqli_num_rows($result_total) > 0) {
        while ($row = mysqli_fetch_assoc($result_total)) {
            array_push($subject[$row["subject_id"]], $row["COUNT(*)"]);
        }
    } else {
        return null;
    }

    $result_present = mysqli_query($conn, $sql_present);
    if (mysqli_num_rows($result_present) > 0) {
        while ($row = mysqli_fetch_assoc($result_present)) {
            array_push($subject[$row["subject_id"]], $row["COUNT(*)"]);
        }
    } else {
        return null;
    }
    return $subject;
}

function getTotalClassAttendanceHod($conn,$year) //hod overall 
{
    $user_id = $_SESSION["uid"];
    $sql1 = "SELECT COUNT(*) FROM attendance_master where student_id IN(select id FROM student WHERE class_id IN (SELECT id FROM class WHERE dept_id IN (select dept_id FROM users where id='$user_id') AND year='$year')) ;";
    $sql2= "SELECT COUNT(*) FROM attendance_master where student_id IN(select id FROM student WHERE class_id IN (SELECT id FROM class WHERE dept_id IN (select dept_id FROM users where id='$user_id')AND year='$year')) AND status='1';";

    // $sql1 = "SELECT COUNT(*) FROM attendance_master where student_id in (select id FROM student WHERE class_id IN (SELECT id FROM class WHERE tutor_id='$user_id'));";
    // $sql2 = "SELECT COUNT(*) FROM attendance_master where student_id in (select id FROM student WHERE class_id IN (SELECT id FROM class WHERE tutor_id='$user_id')) and status='1';";

    $res = array(0, 0);
    $result1 = mysqli_query($conn, $sql1);
    if (mysqli_num_rows($result1) > 0) {
        if ($row = mysqli_fetch_assoc($result1)) {
            $res[0] = $row["COUNT(*)"];
           
        }
    } else {
        return null;
    }
    $result2 = mysqli_query($conn, $sql2);
    if (mysqli_num_rows($result2) > 0) {
        if ($row = mysqli_fetch_assoc($result2)) {
            $res[1] = $row["COUNT(*)"];
            
        }
    } else {
        return null;
    }

    return $res;
}

function getTotalClassAttendance($conn)
{
    $user_id = $_SESSION["uid"];
    $sql1 = "SELECT COUNT(*) FROM attendance_master where student_id in (select id FROM student WHERE class_id IN (SELECT id FROM class WHERE tutor_id='$user_id'));";
    $sql2 = "SELECT COUNT(*) FROM attendance_master where student_id in (select id FROM student WHERE class_id IN (SELECT id FROM class WHERE tutor_id='$user_id')) and status='1';";

    //$sql1 = "SELECT COUNT(*) FROM attendance_master WHERE student_id = '$student_id';";
    //$sql2 = "SELECT COUNT(*) FROM attendance_master WHERE student_id = '$student_id' and status=1;";
    $res = array(0, 0);
    $result1 = mysqli_query($conn, $sql1);
    if (mysqli_num_rows($result1) > 0) {
        if ($row = mysqli_fetch_assoc($result1)) {
            $res[0] = $row["COUNT(*)"];
        }
    } else {
        return null;
    }
    $result2 = mysqli_query($conn, $sql2);
    if (mysqli_num_rows($result2) > 0) {
        if ($row = mysqli_fetch_assoc($result2)) {
            $res[1] = $row["COUNT(*)"];
        }
    } else {
        return null;
    }
    return $res;
}


function getTotalClassAttendanceDept($conn) //for dept in hod pie chart
{
    // $user_id = $_SESSION["uid"];
    // $sql1 = "SELECT COUNT(*) FROM attendance_master where student_id in (select id FROM student WHERE class_id IN (SELECT id FROM class WHERE tutor_id='$user_id'));";
    // $sql2 = "SELECT COUNT(*) FROM attendance_master where student_id in (select id FROM student WHERE class_id IN (SELECT id FROM class WHERE tutor_id='$user_id')) and status='1';";

    $user_id = $_SESSION["uid"];

    $sql1 = "SELECT COUNT(*) FROM attendance_master where student_id IN(select id FROM student WHERE class_id IN (SELECT id FROM class WHERE dept_id IN (select dept_id FROM users where id='$user_id'))) ;";
    $sql2= "SELECT COUNT(*) FROM attendance_master where student_id IN(select id FROM student WHERE class_id IN (SELECT id FROM class WHERE dept_id IN (select dept_id FROM users where id='$user_id'))) AND status='1';";


    //$sql1 = "SELECT COUNT(*) FROM attendance_master WHERE student_id = '$student_id';";
    //$sql2 = "SELECT COUNT(*) FROM attendance_master WHERE student_id = '$student_id' and status=1;";
    $res = array(0, 0);
    $result1 = mysqli_query($conn, $sql1);
    if (mysqli_num_rows($result1) > 0) {
        if ($row = mysqli_fetch_assoc($result1)) {
            $res[0] = $row["COUNT(*)"];
        }
    } else {
        return null;
    }
    $result2 = mysqli_query($conn, $sql2);
    if (mysqli_num_rows($result2) > 0) {
        if ($row = mysqli_fetch_assoc($result2)) {
            $res[1] = $row["COUNT(*)"];
        }
    } else {
        return null;
    }
    return $res;
}


function subjectsList($conn)
{
    $user_id = $_SESSION["uid"];
    $sql = "SELECT * FROM subject WHERE staff_id='$user_id';";

    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        return $result;
    } else {
        return null;
    }
}
function classList($conn)
{
    $user_id = $_SESSION["uid"];
    $sql_subject = "SELECT * FROM subject WHERE class_id IN (SELECT id FROM class WHERE tutor_id = '$user_id');";

    $result = mysqli_query($conn, $sql_subject);
    if (mysqli_num_rows($result) > 0) {
        return $result;
    } else {
        return null;
    }
}

function classLinePlot($conn, $subject_id)
{
    $user_id = $_SESSION["uid"];
    $sql_boys = "SELECT date,COUNT(*) FROM attendance_master where status='1' and subject_id='$subject_id' and student_id IN (SELECT id from student WHERE gender='1' and class_id in (SELECT id from class where tutor_id='$user_id')) GROUP BY date;";
    $sql_girls = "SELECT date,COUNT(*) FROM attendance_master where status='1' and subject_id='$subject_id' and student_id IN (SELECT id from student WHERE gender='0' and class_id in (SELECT id from class where tutor_id='$user_id')) GROUP BY date;";
    $sql_total = "SELECT date,COUNT(*) FROM attendance_master where status='1' and subject_id='$subject_id' and student_id IN (SELECT id from student where class_id in (SELECT id from class where tutor_id='$user_id')) GROUP BY date;";

    $date_array = array();
    $boys_array = array();
    $girls_array = array();
    $total_array = array();

    $result = mysqli_query($conn, $sql_boys);
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            array_push($date_array, $row["date"]);
            array_push($boys_array, $row["COUNT(*)"]);
        }
    } else {
        return null;
    }

    $result = mysqli_query($conn, $sql_girls);
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            array_push($girls_array, $row["COUNT(*)"]);
        }
    } else {
        return null;
    }
    $result = mysqli_query($conn, $sql_total);
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            array_push($total_array, $row["COUNT(*)"]);
        }
    } else {
        return null;
    }
    //echo implode(" ",$date_array)."<br>".implode(" ",$boys_array)."<br>".implode(" ",$girls_array)."<br>".implode(" ",$total_array)."<br>";
    return array($date_array, $boys_array, $girls_array, $total_array);
}


function classLinePlot_subject($conn, $subject_id)
{
    $user_id = $_SESSION["uid"];
    $sql_total = "SELECT date,COUNT(*) FROM attendance_master where subject_id='$subject_id' GROUP BY date;";
    $sql_girls = "SELECT date,COUNT(*) FROM attendance_master where subject_id='$subject_id' and student_id IN(SELECT id from student WHERE gender='0')  GROUP BY date;";
    $sql_boys = "SELECT date,COUNT(*) FROM attendance_master where subject_id='$subject_id' and student_id IN(SELECT id from student WHERE gender='1')  GROUP BY date;";

    $date_array = array();
    $boys_array = array();
    $girls_array = array();
    $total_array = array();

    $result = mysqli_query($conn, $sql_boys);
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            array_push($date_array, $row["date"]);
            array_push($boys_array, $row["COUNT(*)"]);
        }
    } else {
        return null;
    }

    $result = mysqli_query($conn, $sql_girls);
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            array_push($girls_array, $row["COUNT(*)"]);
        }
    } else {
        return null;
    }
    $result = mysqli_query($conn, $sql_total);
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            array_push($total_array, $row["COUNT(*)"]);
        }
    } else {
        return null;
    }
    //echo implode(" ",$date_array)."<br>".implode(" ",$boys_array)."<br>".implode(" ",$girls_array)."<br>".implode(" ",$total_array)."<br>";
    return array($date_array, $boys_array, $girls_array, $total_array);
}

function manualAttendanceLeft($conn, $subject_id)
{
    $date = date("Y-m-d");
    $day = date("w", strtotime($date));
    $sql_students = "SELECT * from student where class_id in (SELECT class_id FROM subject WHERE id='$subject_id');";
    $sql_hour = "SELECT hour from time_table where subject_id='$subject_id' and day='$day';";
    $result_students = mysqli_query($conn, $sql_students);
    $students_array=array();
    $hour_array=array();
    if (mysqli_num_rows($result_students) > 0) {
        while ($row = mysqli_fetch_assoc($result_students)) {
            $students_array[$row["register_number"]]=$row["name"];
        }
    } else {
        return null;
    }
    $result_hour=mysqli_query($conn, $sql_hour);
    if (mysqli_num_rows($result_hour) > 0) {
        //fetch hours which are not marked take hours form checkAttEntry
        while ($row = mysqli_fetch_assoc($result_hour)) {
            //
            $h = $row["hour"];
            $tt_sql = "SELECT id FROM time_table where subject_id='$subject_id' and hour='$h' and day='$day';";
            $result1=mysqli_query($conn, $tt_sql);
            if (mysqli_num_rows($result1) > 0) {
                if ($row1 = mysqli_fetch_assoc($result1)) {
                    $tt = $row1["id"];
                    $att_tt = "select id from attendance_master where time_table_id='$tt' and date='$date' and confirm=1;";
                    $result2=mysqli_query($conn, $att_tt);
                    if (mysqli_num_rows($result2) > 0) {
                        
                    }
                    else{
                        array_push($hour_array, $h);
                    }
                }
            } else {
                return null;
            }
            //
            //array_push($hour_array, $row["hour"]);
        }
    } else {
        return null;
    }
    return array($students_array,$hour_array);
}

function getBackAttendance($conn,$t,$subject,$hour)
{
    $date=date("Y-m-d");
    $sql="SELECT student.register_number,attendance_master.status from attendance_master join student on attendance_master.student_id=student.id where attendance_master.subject_id='$subject' and attendance_master.time_table_id='$t' and date='$date';";
    $attd_result=array();
    $result=mysqli_query($conn, $sql);
    echo mysqli_error($conn);
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $attd_result+=[$row["register_number"]=>$row["status"]];
        }
    } else {
        return null;
    }
    return $attd_result;
}

function checkAttEntry($conn,$subject)
{
    $date=date("Y-m-d");
    $day = date("w", strtotime($date));
    $cnt_hour = 0;
    $cnt_att = 0;
    $sql_hour = "SELECT hour from time_table where subject_id='$subject' and day='$day';";
    $result=mysqli_query($conn, $sql_hour);
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $cnt_hour+=1;
            $h =$row["hour"];
            $tt_sql = "SELECT id FROM time_table where subject_id='$subject' and hour='$h' and day='$day';";
            $result1=mysqli_query($conn, $tt_sql);
            if (mysqli_num_rows($result1) > 0) {
                if ($row = mysqli_fetch_assoc($result1)) {
                    $tt = $row["id"];
                    $att_tt = "select id from attendance_master where time_table_id='$tt' and date='$date';";
                    $result2=mysqli_query($conn, $att_tt);
                    if (mysqli_num_rows($result2) > 0) {
                        $cnt_att += 1;
                    }
                }
            } else {
                return null;
            }
        }
    } 
    else {
        return null;
    }
    if($cnt_att == $cnt_hour){
        return false;
    }
    //TODO check if all hours are completed for that day and confirm=1
    return true;
}

function getLeaveCount($conn)
{
    $user_id = $_SESSION["uid"];

    $sql= "select COUNT(*) from leave_application where student_id in (select id from student where class_id in (select id from class where tutor_id='$user_id')) and status=0;";
    $result = mysqli_query($conn,$sql);
    if(mysqli_num_rows($result)>0){
        if($row = mysqli_fetch_assoc($result)) {
            return $row["COUNT(*)"];
        }
        return -1;
    }
    return -1;
}
function getLeaveApp($conn)
{
    $user_id = $_SESSION["uid"];

    $sql= "select *,leave_application.id as leave_id from leave_application join student on student.id=leave_application.student_id where leave_application.student_id in (select id from student where class_id in (select id from class where tutor_id='$user_id')) and leave_application.status=0;";
    $result = mysqli_query($conn,$sql);
    if(mysqli_num_rows($result)>0){
        return $result;
    }
    return null;
}