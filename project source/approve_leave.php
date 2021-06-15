<?php include_once 'includes/header.php';
?>

<!-- Warning Messages -->
<?php
if (!isset($_SESSION["uid"])) {
    header("Location: index.php?log=0");
}
?>

<br>
<?php
if (isset($_GET["log"])) {
?>
    <br>
    <center>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            Already Logged in!
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    </center>
    <?php
} else if (isset($_GET["approve"])) {
    if ($_GET["approve"] == 0) { ?>
        <br>
        <center>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                The application was approved!
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </center>
    <?php } else { ?>
        <br>
        <center>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                The application was not approved!
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </center>
<?php }
} ?>


<?php
    $res= getLeaveApp($conn);
?>
<div class="container table-responsive-md">
<?php
  if($res==null)
  {
  echo "<h3>No Previous Leave Application</h3>";
  }
else{?>
  <h3>Leave Applications</h3>
    <table class="table table-hover">
      <thead class="thead-dark">
        <tr>
        <th>Register Number</th>
        <th>Name</th>
          <th>Applied On</th>
          <th>Leave Type</th>
          <th>Details</th>
          <th>Approve/Disapprove</th>
        </tr>
      </thead>
      <?php 
      while($row = mysqli_fetch_assoc($res))
      {
      ?>
      <tbody style="background-color:white">
      <?php 
        echo "<td>". $row["register_number"]."</td>";
        echo "<td>". $row["name"]."</td>";
        echo "<td>". $row["applied_on"]."</td>";
        echo "<td>". $row["leave_type"]."</td>";
      ?>
      <td>
        <button class="btn btn-info" onclick="getDetails(`<?php echo $row['days']?>`,`<?php echo $row['from_date']?>`,`<?php echo $row['to_date']?>`,`<?php echo $row['from_session']?>`,`<?php echo $row['to_session']?>`,`<?php echo $row['reason']?>`)">Show Details</button>
      </td>
        <?php 
        if($row["status"]==0) { ?>
        <td>
          <button class="btn btn-success" value="<?php echo $row['leave_id']?>" onclick="approve(this)">Approve</button>
         <button class="btn btn-danger" value="<?php echo $row['leave_id']?>" onclick="disapprove(this)">Disapprove</button></td>
        <?php } ?>
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

<script>
    
    function approve(btn) {
        if(confirm("Do you want to confirm this approval?"))
        {
            $.ajax({
                        url: "includes/approve_leave.inc.php",
                        type: "POST",
                        dataType: "text",
                        data: {
                            "id": btn.value,
            },
            success:function(result)
            {
                if(result==1)
                {
                    location.reload();
                }
                else{
                    alert(result);
                }
            }
        });

        }
    }

    function disapprove(btn) {
        if(confirm("Do you want to confirm this disapproval?"))
        {
            $.ajax({
                        url: "includes/disapprove_leave.inc.php",
                        type: "POST",
                        dataType: "text",
                        data: {
                            "id": btn.value,
            },
            success:function(result)
            {
                if(result==1)
                {
                    location.reload();
                }
                else{
                    alert(result);
                }
                
            }
        });
        }
    }
</script>

<?php include_once 'includes/footer.php' ?>