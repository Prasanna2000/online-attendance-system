<?php include_once 'includes/header.php';
?>

<!-- Warning Messages -->
<?php
if (!isset($_SESSION["uid"])) {
  header("Location: index.php?log=0");
}
?>
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
} ?>
<?php
if (isset($_GET["h"])) {
?>
    <br>
    <center>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            No classes today or Attendance already marked!
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    </center>
<?php
} ?>

<style>
    input[type="date"]:not(.has-value):before {
        color: gray;
        content: attr(placeholder);
    }
</style>

<!--USING BOOTSTRAP 4.0.0-->

<!-- TODO PHP -->
<br>


<!--cards-->
<?php
$result  = subjectsList($conn);
if ($result == null) {
    header("Location: index.php?log=1");
} else if ($result != null) {
?>
    <div class="jumbotron p-4" id="full-content">
        <h2 style="text-align:center">Handling Classes</h2>
        <div class="row">
            <?php
            $cnt = 1;

            while ($row = mysqli_fetch_assoc($result)) {
            ?>
                <div class="column">
                    <a class="sub-anc" href="subject.php?sub=<?php echo $row["name"]; ?>&code=<?php echo $row["id"]; ?>">
                        <!-- data-toggle="tooltip" data-placement="top" data-html="true" title="CSE 1<sup>st</sup> Year"-->
                        <div class="card" style="border-radius:10px">
                            <img src="<?php echo $images[$cnt % 6] ?>" alt="Avatar" style="width:100%;border-radius:10px">
                            <div class="content">
                                <h4><b><?php echo $row["name"] ?></b></h4>
                                <p><?php echo $row["subject_code"] ?></p>
                            </div>
                        </div>
                    </a>
                </div>
            <?php $cnt += 1;
            } ?>
        </div>
    </div>
<?php } ?>

<?php
    if($_SESSION["dept_id"]==2)
    {
        $dept = "Computer Science and Engineering";
    }
    else if($_SESSION["dept_id"]==1){
        $dept = "Civil Engineering";
    }
    else if($_SESSION["dept_id"]==3){
        $dept = "Electronics and communication Engineering";
    }
    else if($_SESSION["dept_id"]==4){
        $dept = "Electrical and Electronics Engineering";
    }
    else if($_SESSION["dept_id"]==5){
        $dept = "Mechanical Engineering";
    }
?>

<div class="jumbotron p-4" id="full-content">
    <h2 style="text-align:center">Computer Science and Engineering</h2>

    </a> <br><br>
    <div class="container-fluid">
        <div id="web-plot" class="container">
            <div class="row" style="margin-left: 17%;">
                <!-- <div class="col">
                    <div id="barPlot"></div>
                </div> -->
                <center>
                <div class="col">
                    <div id="piePlot"></div>
                </div>
            </div>
        </div>
        </center>
        <br>
        <div id="phone-plot" style="width:95%;display:none">
            <div id="barPlot_phone"></div>
            <div id="piePlot_phone"></div>
        </div>
    </div>
    <?php
    $subject = "C++"; //Queried from DB
    $sub_code = "CS1234";
    $class_num = 1;
    $class_count = 4;
    ?>
    <div class="row">
        <div class="column">
        <?php
            $year = 1;
            $result = getTotalClassAttendanceHod($conn, $year);
            $total = $result[0];
            $present = $result[1];
            //echo $result[0],$result[1];
            $presentp = (int)(($present / $total) * 100);
            $absentp = 100 - $presentp;
            $subjectwise_result = getSubjectWiseTotalClassAttendanceHod($conn, $year);
            $subject_name = array();
            $subject_present = array();
            $subject_total = array();

            foreach ($subjectwise_result as $i => $j) {
                array_push($subject_name, $j[0]);
                array_push($subject_present, (int)($j[2] / $j[1] * 100));
                array_push($subject_total, 100);
            }
            $print = "First Year";
            $complete = array($print,$presentp,$absentp,implode(",",$subject_name),implode(",",$subject_present),implode(",",$subject_total));
            $complete = implode("#",$complete);
            ?>
            <a id='class-1' class="sub-anc" href="#class-1" onclick="openSub('<?php echo $complete?>')" style="cursor:pointer">
                <div class="card" style="border-radius:10px">
                    <center><img src="img/graduation-1.png" alt="Avatar" style="width:70%;border-radius:10px"></center>
                    <div class="content">
                        <h4><b>First Year</b></h4>
                    </div>
                </div>
            </a>
        </div>

        <div class="column">
        <?php
            $year = 2;
            $result = getTotalClassAttendanceHod($conn, $year);
            $total = $result[0];
            $present = $result[1];
            //echo $result[0],$result[1];
            $presentp = (int)(($present / $total) * 100);
            $absentp = 100 - $presentp;
            $subjectwise_result = getSubjectWiseTotalClassAttendanceHod($conn, $year);
            $subject_name = array();
            $subject_present = array();
            $subject_total = array();

            foreach ($subjectwise_result as $i => $j) {
                array_push($subject_name, $j[0]);
                array_push($subject_present, (int)($j[2] / $j[1] * 100));
                array_push($subject_total, 100);
            }
            $print = "Second Year";
            $complete = array($print,$presentp,$absentp,implode(",",$subject_name),implode(",",$subject_present),implode(",",$subject_total));
            $complete = implode("#",$complete);
            ?>
            <a id='class-2' class="sub-anc" href="#class-2" onclick="openSub('<?php echo $complete?>')" style="cursor:pointer">
                <div class="card" style="border-radius:10px">
                    <center><img src="img/graduation-2.png" alt="Avatar" style="width:70%;border-radius:10px"></center>
                    <div class="content">
                        <h4><b>Second Year</b></h4>
                    </div>
                </div>
            </a>
        </div>

        <div class="column">
        <?php
            $year = 3;
            $result = getTotalClassAttendanceHod($conn, $year);
            $total = $result[0];
            $present = $result[1];
            //echo $result[0],$result[1];
            $presentp = (int)(($present / $total) * 100);
            $absentp = 100 - $presentp;
            $subjectwise_result = getSubjectWiseTotalClassAttendanceHod($conn, $year);
            $subject_name = array();
            $subject_present = array();
            $subject_total = array();

            foreach ($subjectwise_result as $i => $j) {
                array_push($subject_name, $j[0]);
                array_push($subject_present, (int)($j[2] / $j[1] * 100));
                array_push($subject_total, 100);
            }
            $print = "Third Year";
            $complete = array($print,$presentp,$absentp,implode(",",$subject_name),implode(",",$subject_present),implode(",",$subject_total));
            $complete = implode("#",$complete);
            ?>
            <a id='class-2' class="sub-anc" href="#class-2" onclick="openSub('<?php echo $complete?>')" style="cursor:pointer">
                <div class="card" style="border-radius:10px">
                    <center><img src="img/graduation-3.png" alt="Avatar" style="width:70%;border-radius:10px"></center>
                    <div class="content">
                        <h4><b>Third Year</b></h4>
                    </div>
                </div>
            </a>
        </div>

        <div class="column">
        <?php
            $year = 4;
            $result = getTotalClassAttendanceHod($conn, $year);
            $total = $result[0];
            $present = $result[1];
            //echo $result[0],$result[1];
            $presentp = (int)(($present / $total) * 100);
            $absentp = 100 - $presentp;
            $subjectwise_result = getSubjectWiseTotalClassAttendanceHod($conn, $year);
            $subject_name = array();
            $subject_present = array();
            $subject_total = array();

            foreach ($subjectwise_result as $i => $j) {
                array_push($subject_name, $j[0]);
                array_push($subject_present, (int)($j[2] / $j[1] * 100));
                array_push($subject_total, 100);
            }
            $print = "Fourth Year";
            $complete = array($print,$presentp,$absentp,implode(",",$subject_name),implode(",",$subject_present),implode(",",$subject_total));
            $complete = implode("#",$complete);
            ?>
            <a id='class-2' class="sub-anc" href="#class-2" onclick="openSub('<?php echo $complete?>')" style="cursor:pointer">
                <div class="card" style="border-radius:10px">
                    <center><img src="img/graduation-4.png" alt="Avatar" style="width:70%;border-radius:10px"></center>
                    <div class="content">
                        <h4><b>Fourth Year</b></h4>
                    </div>
                </div>
            </a>
        </div>
    </div>

        <div class="card" id="yearplot" style="display:none">
            <div class="container-fluid">
            <center>
                <h3 id="year-name"></h3>
                <div id="web-plot-year" class="container">
                    <div class="row">
                        <div class="col">
                            <div id="barPlot_year"></div>
                        </div>
                        <div class="col">
                            <div id="piePlot_year"></div>
                        </div>
                    </div>
                </div>
                <br>
                <div id="phone-plot-year" style="width:95%;display:none">
                    <div id="barPlot_phone_year"></div>
                    <div id="piePlot_phone_year"></div>
                </div>
                </center>

            </div>
        </div>
</div>





<?php include_once 'includes/footer.php' ?>

</body>

</html>

<script>
    function openSub(complete) {
        complete = complete.split("#");
        class_num = complete[0].split(",");
        presentp = complete[1]
        absentp = complete[2]
        subject_name = complete[3].split(",");
        subject_present = complete[4].split(",");
        subject_total = complete[5].split(",");
        document.getElementById("yearplot").style.display = "block";
        document.getElementById('year-name').innerHTML = class_num;
        
    var colorway_colors = ["green", "tomato"];
    if (screen.height <= 680) {
        document.getElementById("phone-plot-year").style.display = "block";
        document.getElementById("web-plot-year").style.display = "none";
    }

    function show_bar_year() {

        var attd = subject_present;
       // var attd = [20, 60, 40, 80, 60, 97];
        var attendance = {
            x: subject_name,
            //x: ["Compiler Design", "Bala", "Distributed Systems", "Internet Prog", "Data Structures", "Mobile Comp"],
            y: attd,
            name: 'Present',
            type: 'bar'
        }

        //var tot = [100, 100, 100, 100, 100, 100];
        var tot = subject_total
        for (let i = 0; i < attd.length; i++)
            tot[i] -= attd[i];
        var total = {
            x: subject_name,
           // x: ["Compiler Design", "Bala", "Distributed Systems", "Internet Prog", "Data Structures", "Mobile Comp"],
            y: tot,
            name: 'Absent',
            type: 'bar'
        }

        var data = [attendance, total];
        var layout = {
            //colorway: colorway_colors,
            barmode: 'relative',
            title: 'Subject Wise Class Attendance',
            xaxis: {
                tickangle: 40,
                automargin: true
            }
        };

        var config = {
            responsive: true
        };

        Plotly.newPlot('barPlot_year', data, layout, config);
        Plotly.newPlot('barPlot_phone_year', data, layout, config);
    }

    function show_pie_year() {
        var data = [{
            values: [presentp, absentp],
            //values: [65, 35],
            labels: ['Present', 'Absent'],
            domain: {
                column: 0
            },
            hoverinfo: 'label+percent',
            hole: .5,
            type: 'pie'
        }];

        var layout = {
            title: 'Overall Class Attendance',
            //colorway: colorway_colors,
        };

        var config = {
            responsive: true,
            autosize: true
        };

        Plotly.newPlot('piePlot_year', data, layout, config);
        Plotly.newPlot('piePlot_phone_year', data, layout, config);
    }

    show_pie_year();
    show_bar_year();
        
    }
</script>

<?php

$result = getTotalClassAttendanceDept($conn);
$total = $result[0];
$present = $result[1];
$presentp = (int)(($present / $total) * 100);
$absentp = 100 - $presentp;
?>
<script>
    var colorway_colors = ["green", "tomato"];
    if (screen.height <= 680) {
        document.getElementById("phone-plot").style.display = "block";
        document.getElementById("web-plot").style.display = "none";
    }

    function show_pie() {
        var data = [{
            values: [parseInt('<?php echo $presentp; ?>'), parseInt('<?php echo $absentp; ?>')],
            //values: [65, 35],
            labels: ['Present', 'Absent'],
            domain: {
                column: 0
            },
            hoverinfo: 'label+percent',
            hole: .5,
            type: 'pie'
        }];

        var layout = {
            title: 'Overall Department Attendance',
            colorway: colorway_colors,

            // height: 400,
            // width: 700,
            // autosize: true
        };

        var config = {
            responsive: true
        };

        Plotly.newPlot('piePlot', data, layout, config);
        Plotly.newPlot('piePlot_phone', data, layout, config);
    }

   // show_bar();
    show_pie();
</script>
<!-- 
<script>
    var colorway_colors = ["green", "tomato"];
    if (screen.height <= 680) {
        document.getElementById("phone-plot-year").style.display = "block";
        document.getElementById("web-plot-year").style.display = "none";
    }

    function show_bar_year() {

        var attd = [20, 60, 40, 80, 60, 97];
        var attendance = {
            x: ["Compiler Design", "AI", "Distributed Systems", "Internet Prog", "Data Structures", "Mobile Comp"],
            y: attd,
            name: 'Present',
            type: 'bar'
        }

        var tot = [100, 100, 100, 100, 100, 100];
        for (let i = 0; i < attd.length; i++)
            tot[i] -= attd[i];
        var total = {
            x: ["Compiler Design", "AI", "Distributed Systems", "Internet Prog", "Data Structures", "Mobile Comp"],
            y: tot,
            name: 'Absent',
            type: 'bar'
        }

        var data = [attendance, total];
        var layout = {
            //colorway: colorway_colors,
            barmode: 'relative',
            title: 'Subject Wise Class Attendance',
            xaxis: {
                tickangle: 40,
                automargin: true
            }
        };

        var config = {
            responsive: true
        };

        Plotly.newPlot('barPlot_year', data, layout, config);
        Plotly.newPlot('barPlot_phone_year', data, layout, config);
    }

    function show_pie_year() {
        var data = [{
            values: [65, 35],
            labels: ['Present', 'Absent'],
            domain: {
                column: 0
            },
            hoverinfo: 'label+percent',
            hole: .5,
            type: 'pie'
        }];

        var layout = {
            title: 'Overall Class Attendance',
            //colorway: colorway_colors,
        };

        var config = {
            responsive: true,
            autosize: true
        };

        Plotly.newPlot('piePlot_year', data, layout, config);
        Plotly.newPlot('piePlot_phone_year', data, layout, config);
    }

    show_pie_year();
    show_bar_year();
</script> -->