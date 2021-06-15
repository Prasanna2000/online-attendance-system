<?php 
// Include the database config file 
require_once 'dbh.inc.php'; 
 
$subject = $_POST["subject_id"];
$date = $_POST["date"];
$day = date("w", strtotime($date));
$hour = $_POST["hour"];


// Filter the excel data 
// function filterData(&$str){ 
//     $str = preg_replace("/\t/", "\\t", $str); 
//     $str = preg_replace("/\r?\n/", "\\n", $str); 
//     if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"'; 
// } 
 
// Excel file name for download 
$fileName = "attendance-" . $date . ".csv"; 
 
// Column names 
$fields = array('STUDENT NAME', 'REGISTER NUMBER','ATTENDANCE'); 
 
// Display column names as first row 
$excelData = implode(",", array_values($fields)) . "\n"; 
 
// Get records from the database 
$sql = "SELECT student.name,student.register_number, attendance_master.status
FROM attendance_master
JOIN student
ON student.id=attendance_master.student_id WHERE date='$date' and time_table_id in (select id from time_table where day='$day' and subject_id='$subject' and hour='$hour');";


$result = mysqli_query($conn,$sql);
if(mysqli_num_rows($result) > 0){ 
    // Output each row of the data 
    while($row = mysqli_fetch_assoc($result)){
        $status = strcmp($row['status'],1)==0?"Present":"Absent";
        $rowData = array($row['name'], "'".$row['register_number'],$status); 
        $rowData = implode(",",$rowData);
        //array_walk($rowData, 'filterData'); 
        $excelData .= $rowData . "\n"; 
    } 
}else{ 
    $excelData .= 'No records found...'. "\n"; 
     
} 
 
// Headers for download 
header("Content-Disposition: attachment; filename=\"$fileName\""); 
header("Content-Type: application/vnd.ms-excel"); 
 
// Render excel data 
echo $excelData; 
 
exit;