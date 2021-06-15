<?php include_once 'includes/stud_header.php' ?>

<!-- Warning Messages -->
<?php
if (!isset($_SESSION["regno"])) {
  header("Location: index.php?log=0");
}
?>

<!--USING BOOTSTRAP 4.0.0-->
<style>
  tbody {
    background-color: white;
  }
</style>
<?php //echo $_SESSION["name"]."<br>".$_SESSION["regno"];
require_once 'includes/dbh.inc.php';
require_once 'includes/functions.inc.php';
$res = getTimeTable($conn);
if($res==null){
  header("Location: index.php?log=1");
  exit();
}
$arr=array(array("","","","","",""),array("","","","","",""),array("","","","","",""),array("","","","","",""),array("","","","","",""),array("","","","","",""));
while($row = mysqli_fetch_assoc($res))
{
  $hours = explode(",",$row["hour"]);
  foreach($hours as $key=>$val){
    $arr[(int)$row["day"]-1][(int)$val-1] = $row["subject_abbr"];
  } 
}
?>
<center>
  <div id="table" style="overflow:auto;margin:5px">
    <table class="table table-hover table-bordered" style=" margin:auto;width: 40%;">
      <thead>
        <tr style="background-color:black;color:white">
          <th scope="col">#</th>
          <th scope="col">1</th>
          <th scope="col">2</th>
          <th scope="col">3</th>
          <th scope="col">4</th>
          <th scope="col">5</th>
          <th scope="col">6</th>
        </tr>
      </thead>
      <tbody>
            <?php
              $days = array("Monday","Tuesday","Wednesday","Thursday","Friday","Saturday");
              for($i=0;$i<6;$i+=1)
              {
                
                echo "<tr>";
                echo "<th scope='row'>".$days[$i]."</th>";
                for($j=0;$j<6;$j+=1)
                {
                  echo "<td>".$arr[$i][$j]. "</td>";
                }
              }
              echo "</tr>";
            ?>
      </tbody>
    </table>
  </div>
</center>
<?php 
include_once 'includes/footer.php' ?>

</body>

</html>