<?php include_once 'includes/stud_header.php' ?>

<!-- Warning Messages -->
<?php
if (!isset($_SESSION["regno"])) {
  header("Location: index.php?log=0");
}
?>
<?php
if (isset($_GET["log"])) {
?>
  <center>
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
      Already Logged in!
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
  </center>
<?php
} else if (isset($_GET["leave"])) {
?>
  <center>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      Leave application posted successfully!
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
  </center>
<?php
} ?>
<!--USING BOOTSTRAP 4.0.0-->
<?php //echo $_SESSION["name"]."<br>".$_SESSION["regno"];
?>
<style>
  .card {
    margin-left: 15px;
    margin-bottom: 15px;
    width: 30%;
  }

  .card1 {
    margin-left: 20%;
  }

  #card-title:hover {
    color: gray;
  }



  @media screen and (max-width: 680px) {
    .card {
      margin-left: 15px;
      margin-bottom: 15px;
      width: 18rem;
      height: 10%;
    }

  }
</style>

<div>
  <center>
  <!-- <div class="jumbotron jumbotron-fluid">
  <h4>Prasanna A</h4>
  <h5>715518104035</h5>
  </div> -->
    <div class="row" style="margin:auto;width:80%">
      <a class="card card1" href="apply_leave.php" style="text-decoration: none;color:black">
        <div>
          <div class="card-body" style="padding:2px">
            <h5 class="card-title">Apply for Leave</h5>
            <img class="card-subtitle mb-2 text-muted" src="img/calendar.png" alt="bar-icon" width="30%">
          </div>
        </div>
      </a>
      <a class="card" href="timetable.php" style="text-decoration: none;color:black">
        <div>
          <div class="card-body" style="padding:2px">
            <h5 class="card-title">Time Table</h5>
            <img class="card-subtitle mb-2 text-muted" src="img/worksheet.png" alt="bar-icon" width="30%">
          </div>
        </div>
      </a>
    </div>

    <div id="web-plot" class="container">
      <div class="row">
        <div class="col">
          <div id="barPlot"></div>
        </div>
        <div class="col">
          <div id="piePlot"></div>
        </div>
      </div>
    </div>

    <div id="phone-plot" style="width:95%;display:none">
      <div id="barPlot_phone"></div>
      <div id="piePlot_phone"></div>
    </div>

  </center>

</div>
<?php
      $result = getTotalAttendance($conn);
      $total = $result[0];
      $present = $result[1]; 
      $presentp = (int)(($present/$total)*100);
      $absentp = 100 - $presentp;

      $subjectwise_result = getSubjectWiseTotalAttendance($conn);
      $subject_name = array();
      $subject_present = array();
      $subject_total = array();

      foreach($subjectwise_result as $i)
      {
        array_push($subject_name,$i[0]);
        if(array_key_exists(2,$i)){
          array_push($subject_present,(int)($i[2]/$i[1]*100));
        }
        else{
          array_push($subject_present,(int)(0));
        }
        array_push($subject_total,100);
      }
  ?>
<script>
  var colorway_colors = ["green", "tomato"];
  if (screen.height <= 680) {
    document.getElementById("phone-plot").style.display = "block";
    document.getElementById("web-plot").style.display = "none";
  }

  
  function show_bar() {
   
      var attd = "<?php  echo implode(",",$subject_present);?>".split(",");
   // var attd = [30, 60, 40, 80, 60, 97];


    var attendance = {
      x : "<?php  echo implode(",",$subject_name) ;?>".split(","),
      //x: ["Compiler Design", "AI", "Distributed Systems", "Internet Prog", "Data Structures", "Mobile Comp"],
      y: attd,
      name: 'Present',
      type: 'bar'
    }

    var tot = "<?php  echo implode(",",$subject_total); ?>".split(",");
    //var tot = [100, 100, 100, 100, 100, 100];
    for (let i = 0; i < attd.length; i++)
      tot[i] -= attd[i];
    var total = {
      x : "<?php  echo implode(",",$subject_name) ;?>".split(","),
      //x: ["Compiler Design", "AI", "Distributed Systems", "Internet Prog", "Data Structures", "Mobile Comp"],
      y: tot,
      name: 'Absent',
      type: 'bar'
    }

    var data = [attendance, total];
    var layout = {
      colorway: colorway_colors,
      barmode: 'relative',
      title: 'Subject wise Attendance',
      xaxis: {
        tickangle: 40,
        automargin: true
      }
      // height: 400,
      // width: 700,
      // autosize: true
    };

    var config = {
      responsive: true
    };

    Plotly.newPlot('barPlot', data, layout, config);
    Plotly.newPlot('barPlot_phone', data, layout, config);
  }

  function show_pie() {
    
    var data = [{
      values: [parseInt('<?php echo $presentp;?>'), parseInt('<?php echo $absentp;?>')],
      labels: ['Present', 'Absent'],
      domain: {
        column: 0
      },
      hoverinfo: 'label+percent',
      hole: .5,
      type: 'pie'
    }];

    var layout = {
      title: 'Overall Attendance',
      colorway: colorway_colors,
    };

    var config = {
      responsive: true
    };

    Plotly.newPlot('piePlot', data, layout, config);
    Plotly.newPlot('piePlot_phone', data, layout, config);

  }

  show_bar();
  show_pie();
</script>


<?php include_once 'includes/footer.php' ?>