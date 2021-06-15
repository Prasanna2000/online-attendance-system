<?php include_once 'includes/stud_header.php' ?>

<style>
  input[type="date"]:not(.has-value):before {
    color: gray;
    content: attr(placeholder);
  }


  .select {
    width: 20%;
    padding: 3px;
  }

  @media screen and (max-width: 680px) {
    .select {
      width: 40%;
    }
  }
</style>

<!-- Warning Messages -->
<?php
if (!isset($_SESSION["regno"])) {
  header("Location: index.php?log=0");
}
?>
<!--USING BOOTSTRAP 4.0.0-->
<?php //echo $_SESSION["name"]."<br>".$_SESSION["regno"];
?>

<!-- TODO -->
<!-- UPLOAD CERTIFICATE OR MEDICAL CERTIFICATE for OD -->
<!-- Add Medical as a type of leave -->
<!-- Create table for leave entry -->


<div class="d-flex justify-content-center">
  <div id="content-card" class="card" style="width: 45%;margin:5px">
    <!-- SELECT SINGLE OR MANY DAYS -->
    <form action="" method="post">
      <?php if (isset($_POST["many"])) { ?>
        <input id="single-btn" class="btn btn-outline-info" type="submit" name="single" value="Single Day">
        <input id="many-btn" class="btn btn-info" type="submit" name="many" value="Continuous Days">
      <?php } else { ?>
        <input id="single-btn" class="btn btn-info" type="submit" name="single" value="Single Day">
        <input id="many-btn" class="btn btn-outline-info" type="submit" name="many" value="Continuous Days">
      <?php } ?>
    </form>
    <br>


    <!-- LEAVE APPLICATION -->
    <?php if (isset($_POST["many"])) { ?>
      <form action="includes/leave.inc.php" method="post">

        <select id="leave-type" class="select" name="leave-type" required>
          <option value="" selected disabled hidden>Leave Type</option>
          <option value="leave">Leave</option>
          <option value="od">On Duty</option>
          <!-- <option value="med">Medical</option> -->
        </select> <br><br>

        <input placeholder="From:&nbsp;" type="date" name="from-date" id="date" required />
        <select class="select" name="from-session" required>
          <option value="full">Full Day</option>
          <option value="half">Half Day</option>
        </select> <br><br>

        <input placeholder="To:&ensp;&ensp;&nbsp;&nbsp;" name="to-date" type="date" id="date" required />
        <select class="select" name="to-session" required>
          <option value="full">Full Day</option>
          <option value="half">Half Day</option>
        </select> <br><br>

        <textarea id="reason" placeholder="Reason" name="reason" style="width:80%" required></textarea>
        <br><br>
        <input class="btn btn-outline-success" type="submit" name="many-submit">
      </form>

    <?php } else { ?>
      <form action="includes/leave.inc.php" method="post">

        <select id="leave-type" class="select" name="leave-type" required>
          <option value="" selected disabled hidden>Leave Type</option>
          <option value="leave">Leave</option>
          <option value="od">On Duty</option>
          <!-- <option value="med">Medical</option> -->
        </select> <br><br>

        <input placeholder="Date:&nbsp;" type="date" name="date" id="date" required />
        <select class="select" name="session" required>
          <option value="full">Full Day</option>
          <option value="half">Half Day</option>
        </select> <br><br>

        <textarea id="reason" placeholder="Reason" name="reason" style="width:80%" required></textarea>
        <br><br>
        <input class="btn btn-outline-success" type="submit" name="single-submit">
      </form>
    <?php } ?>

  </div>
</div>

<?php //echo $_SESSION["name"]."<br>".$_SESSION["regno"];
require_once 'includes/dbh.inc.php';
require_once 'includes/functions.inc.php';
$res = getappliedLeave($conn);
?>
<div class="d-flex justify-content-center">
  <div id="content-card" class="card" style="width: 45%;margin:5px">
  <?php
  if($res==null)
  {
  echo "<h3>No Previous Leave Application</h3>";
  }
else{?>
  <h3>Past Applications</h3>
    <table class="table table-hover">
      <thead class="thead-dark">
        <tr>
          <th>Applied On</th>
          <th>Leave Type</th>
          <th>Details</th>
          <th>Status</th>
        </tr>
      </thead>
      <?php 
      while($row = mysqli_fetch_assoc($res))
      {
      ?>
      <tbody style="background-color:white">
      <?php 
        echo "<td>". $row["applied_on"]."</td>";
        echo "<td>". $row["leave_type"]."</td>";
      ?>
      <td>
        <button class="btn btn-info" onclick="getDetails(`<?php echo $row['days']?>`,`<?php echo $row['from_date']?>`,`<?php echo $row['to_date']?>`,`<?php echo $row['from_session']?>`,`<?php echo $row['to_session']?>`,`<?php echo $row['reason']?>`)">Show Details</button>
      </td>
      <td>
        <?php if($row["status"]==1){ ?> <!-- Checking if the application is approved or not -->
        <button class="btn btn-success" style="pointer-events: none;">Approved</button>
        <?php } else if($row["status"]==-1) { ?>
          <button class="btn btn-danger" style="pointer-events: none;">Not Approved</button>
        <?php } else if($row["status"]==0) { ?>
          <button class="btn btn-warning" style="pointer-events: none;">Pending</button>
        <?php } ?>
      </td>
      </tbody>
      <?php } ?>
    </table>
  </div>
  <?php } ?>
</div>

<script>
  function getDetails(days,from,to,from_s,to_s,reason) {
    var leave_type = days;

    if (leave_type == "0") {
      var an_fn = "FN";
      if(from_s == "full")
      {
        an_fn = "AN";
      }

      var display = "Date: " + from + "\n" +
        "From: " + an_fn + "\n" +
        "Reason: " + reason;

      alert(display);
    } else if (leave_type == "1") {
      
      var from_an_fn = "FN";
      var to_an_fn = "FN";

      if(from_s =="full")
      {
        from_an_fn="AN";
      }
      if(to_s =="full")
      {
        to_an_fn="AN";
      }

      var display = "From: " + from + " " + from_an_fn + "\n" +
        "To: " + to + " " + to_an_fn + "\n" +
        "Reason: " + reason;

      alert(display);

    }
  }
  if (window.history.replaceState) {
    window.history.replaceState(null, null, window.location.href);
  }
  if (screen.width < 700) {
    document.getElementById("content-card").style.width = "95%";
  }
</script>


<?php include_once 'includes/footer.php' ?>