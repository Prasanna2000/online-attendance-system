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

    .custom-btn-primary {
        background-color: #375a7f;
        border-color: #375a7f;
        color: #fff;
    }

    .custom-btn-primary:hover {
        color: #fff;
        background-color: #2b4764;
        border-color: #28415b;
    }

    .custom-btn-primary:focus,
    .custom-btn-primary.focus {
        color: #fff;
        background-color: #2b4764;
        border-color: #28415b;
        box-shadow: 0 0 0 0.2rem rgba(85, 115, 146, 0.5);
    }

    .custom-btn-primary.disabled,
    .custom-btn-primary:disabled {
        color: #fff;
        background-color: #375a7f;
        border-color: #375a7f;
    }

    .custom-btn-primary:not(:disabled):not(.disabled):active,
    .custom-btn-primary:not(:disabled):not(.disabled).active,
    .show>.custom-btn-primary.dropdown-toggle {
        color: #fff;
        background-color: #28415b;
        border-color: #243a53;
    }

    .custom-btn-primary:not(:disabled):not(.disabled):active:focus,
    .custom-btn-primary:not(:disabled):not(.disabled).active:focus,
    .show>.custom-btn-primary.dropdown-toggle:focus {
        box-shadow: 0 0 0 0.2rem rgba(85, 115, 146, 0.5);
    }
</style>
<!--USING BOOTSTRAP 4.0.0-->

<!-- TODO PHP -->
<br>

<?php
$result  = subjectsList($conn);
if ($result == null) {
    header("Location: index.php?log=1");
} else if ($result != null) {
?>
    <!--cards-->
    <div class="jumbotron p-4" id="full-content">
        <h2 style="text-align:center">Handling Classes</h2>
        <div class="row">
            <?php
            $cnt = 1;

            while ($row = mysqli_fetch_assoc($result)) {
            ?>
                <div class="column">
                    <a class="sub-anc" href="subject.php?sub=<?php echo $row["name"]; ?>&code=<?php echo $row["id"];?>">
                        <!-- data-toggle="tooltip" data-placement="top" data-html="true" title="CSE 1<sup>st</sup> Year"-->
                        <div class="card" style="border-radius:10px">
                            <img src="<?php echo $images[$cnt % 4] ?>" alt="Avatar" style="width:100%;border-radius:10px">
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

<div class="jumbotron p-4" id="full-content">
    <h2 style="text-align:center">CSE 3<sup>rd</sup> Year</h2>
<?php 
$result = getLeaveCount($conn);
if($result==0)
{?>
<a href="approve_leave.php" style="pointer-events: none">
            <button type="button" class="btn custom-btn-primary disabled">
                Leave Approval <span class="badge badge-success">0</span>
            </button>
        </a>
<?php
}else if($result<10 && $result>0)
{?>
    <a href="approve_leave.php">
        <button type="button" class="btn custom-btn-primary" data-toggle="tooltip" title="Pending Leave Approvals">
            Leave Approval <span class="badge badge-warning"><?php echo $result?></span>
        </button>
    </a>
<?php }else if($result>10)
{?>
<a href="approve_leave.php">
        <button type="button" class="btn custom-btn-primary" data-toggle="tooltip" title="Pending Leave Approvals">
            Leave Approval <span class="badge badge-danger"><?php echo $result?></span>
        </button>
    </a>
<?php }
?>
     <br><br>
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
    <br>

    <div id="phone-plot" style="width:95%;display:none">
        <div id="barPlot_phone"></div>
        <div id="piePlot_phone"></div>
    </div>

    <?php

    $result = classList($conn);
    if ($result == null) {
        header("Location: index.php?log=1");
    } else if ($result != null) {

    ?>
        <div class="row">
            <?php
            $cnt = 1;

            while ($row = mysqli_fetch_assoc($result)) {
                $class_num = $cnt;
                $subject_id = $row["id"];
                $subject = $row["name"];
                $sub_code = $row["subject_code"];

                $result_array = classLinePlot($conn, $subject_id);

                $date_array = implode(",", $result_array[0]);

                $boys_array = implode(",", $result_array[1]);

                $girls_array = implode(",", $result_array[2]);

                $total_array = implode(",", $result_array[3]);

                $complete = array($subject, $date_array, $boys_array, $girls_array, $total_array, $subject_id);
                $complete = implode("#", $complete);
            ?>
                <div class="column">
                    <a id="class-<?php echo $class_num ?>" class="sub-anc" href="#class-<?php echo $class_num ?>" onclick="openSub('<?php echo $complete; ?>')" style="cursor:pointer">
                        <div class="card" style="border-radius:10px">
                            <img src="<?php echo $images[$cnt % 4] ?>" alt="Avatar" style="width:100%;border-radius:10px">
                            <div class="content">
                                <h4><b><?php echo $subject ?></b></h4>
                                <p><?php echo $sub_code ?></p>
                            </div>
                        </div>
                    </a>
                </div>
            <?php $cnt += 1;
            } ?>
        </div>
    <?php } ?>

    <div id="classplot" class="container-fluid card" style="display:none">
        <h3 id="class-name"></h3>
        <div class="d-flex justify-content-center">
            <div id="linePlot"></div>
        </div>

        <form action="includes/download.inc.php" method="POST">
        <input type="date" id="table_date" name="date" placeholder="Date:&ensp;"> <br><br>
        <select id="edithour" name="hour" class="select2" style="width:20%" required>
                            <option value="" selected disabled>Select an hour</option>
                            <option value="1">First Hour : 1</option>
                            <option value="2">Second Hour : 2</option>
                            <option value="3">Third Hour : 3</option>
                            <option value="4">Fourth Hour : 4</option>
                            <option value="5">Fifth Hour : 5</option>
                            <option value="6">Sixth Hour : 6</option>
                        </select><br><br>
        
        <center><input type="text" id="table_regno" placeholder="Register Number" style="display: none;">
            <input type="text" id="table_name" placeholder="Student Name" style="display: none;">
        </center><br><br>
        <input type="hidden" id="subject_id_hidden" name="subject_id">
        <div class="d-flex flex-row justify-content-center">
        <input type="button" id="table_go" value="Go" name="" class="btn btn-outline-info p-2">&ensp;
        <input id="export" type="submit" value="Export data" class="btn btn-outline-info p-2" style="display:none">
        </div>
        <br><br>
        </form>
        <div class="container">
            <div class="d-flex justify-content-center align-items-center">
               
                <table id="total_attendance_table" class="table table-hover " style="margin:auto;display:none">
                    <thead>
                        <tr style="background-color:black;color:white">
                            <th scope="col">Register Number</th>
                            <th scope="col">Name</th>
                            <th scope="col">Attendance</th>
                        </tr>
                    </thead>
                    <tbody id="attendance_table"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>





<?php include_once 'includes/footer.php' ?>

</body>

</html>

<script>
    var subject_id = "";

    function openSub(complete) {
        complete = complete.split("#");
        document.getElementById("classplot").style.display = "block";
        document.getElementById('class-name').innerHTML = complete[0];

        subject_id = complete[5];
        document.getElementById("subject_id_hidden").value = subject_id;
        linePlotGraph(complete[1].split(","), complete[2].split(","), complete[3].split(","), complete[4].split(","));


    }

    var student_name = [];
    var register_number = [];
    var student_status = [];
    $(document).ready(function() {
        $("#table_go").click(function() {
            var date = document.getElementById("table_date").value;
            var hour = $("#edithour").select2().val();
            console.log(hour);
            if (date.length == 0) {
                alert("Enter date");
            }
            else if(hour == null){
                alert("Enter hour");
            }
            else {
                hour = hour.toString();
                console.log(hour)
                $.ajax({
                    url: "includes/show_table.php",
                    type: "POST",
                    dataType: "text",
                    data: {
                        "date": date,
                        "subject_id": subject_id,
                        "hour" : hour
                    },
                    success: function(result) {
                    try{
                        result = JSON.parse(result);

                        student_name = result["name"].slice(1, -1).split(",");
                        register_number = result["reg_num"].slice(1, -1).split(",");
                        student_status = result["attendance"].slice(1, -1).split(",");
                        document.getElementById("table_name").style.display = "block";
                        document.getElementById("table_regno").style.display = "block";
                        document.getElementById("total_attendance_table").style.display = "block";
                        document.getElementById("attendance_table").innerHTML = "";

                        build_table(register_number, student_name, student_status);
                        document.getElementById("export").style.display = "block";
                    }
                     catch(error){
                         console.log(error);
                        alert("Enter correct date/hour");
                     }   
                    }
                });
            }
        });

    });

    function build_table(register_number, student_name, student_status) {
        document.getElementById("attendance_table").innerHTML ="";
        for (var i = 0; i < student_name.length; i++) {
            var print_status = '';
            if (student_status[i] == 1) {
                var print_status = '<button class="btn btn-success" style="pointer-events:none">Present</button>';
            } else {
                var print_status = '<button class="btn btn-danger" style="pointer-events:none">Absent</button>';
            }
            var row = `<tr>
                            <td>${register_number[i]}</td>
                            <td>${student_name[i]}</td>
                            <td>${print_status}</td></tr>`;
           
            document.getElementById("attendance_table").innerHTML += row;


        }
    }


    function search_table_name(value, register_number, student_name, student_status) {
       document.getElementById("table_regno").value="";
        var reg_no = [];
        var stud_name = [];
        var stud_status = [];
        value = value.toLowerCase();
        for (var i = 0; i < student_name.length; i++) {
            var name = student_name[i].toLowerCase();
            if (name.includes(value)) {
                reg_no.push(register_number[i]);
                stud_name.push(student_name[i]);
                stud_status.push(student_status[i]);
            }
        }
        return [reg_no, stud_name, stud_status];
    }

    $(document).ready(function() {
        $("#table_name").on("keyup", function() {
            var value = document.getElementById("table_name").value;
            var data = search_table_name(value, register_number, student_name, student_status);
            build_table(data[0], data[1], data[2]);
        });
    });

    function search_table_reg(value, register_number, student_name, student_status) {
        document.getElementById("table_name").value="";
        var reg_no = [];
        var stud_name = [];
        var stud_status = [];
        for (var i = 0; i < student_name.length; i++) {
            var name = register_number[i];
            
            if (name.includes(value)) {
                reg_no.push(register_number[i]);
                stud_name.push(student_name[i]);
                stud_status.push(student_status[i]);
            }
        }
        return [reg_no, stud_name, stud_status];
    }

    $(document).ready(function() {
        $("#table_regno").on("keyup", function() {
            var value = document.getElementById("table_regno").value;
            var data = search_table_reg(value, register_number, student_name, student_status);
            build_table(data[0], data[1], data[2]);
        });
    });
</script>


<?php

$result = getTotalClassAttendance($conn);
$total = $result[0];
$present = $result[1];
$presentp = (int)(($present / $total) * 100);
$absentp = 100 - $presentp;
$subjectwise_result = getSubjectWiseTotalClassAttendance($conn);
$subject_name = array();
$subject_present = array();
$subject_total = array();

foreach ($subjectwise_result as $i) {
    array_push($subject_name, $i[0]);
    array_push($subject_present, (int)($i[2] / $i[1] * 100));
    array_push($subject_total, 100);
}
?>
<script>
    var colorway_colors = ["green", "tomato"];
    if (screen.height <= 680) {
        document.getElementById("phone-plot").style.display = "block";
        document.getElementById("web-plot").style.display = "none";
    }

    function show_bar() {

        var attd = "<?php echo implode(",", $subject_present); ?>".split(",");
        // var attd = [20, 60, 40, 80, 60, 97];
        var attendance = {
            x: "<?php echo implode(",", $subject_name); ?>".split(","),
            //x: ["Compiler Design", "AI", "Distributed Systems", "Internet Prog", "Data Structures", "Mobile Comp"],
            y: attd,
            name: 'Present',
            type: 'bar'
        }

        var tot = "<?php echo implode(",", $subject_total); ?>".split(",");
        // var tot = [100, 100, 100, 100, 100, 100];
        for (let i = 0; i < attd.length; i++)
            tot[i] -= attd[i];
        var total = {
            x: "<?php echo implode(",", $subject_name); ?>".split(","),
            //x: ["Compiler Design", "AI", "Distributed Systems", "Internet Prog", "Data Structures", "Mobile Comp"],
            y: tot,
            name: 'Absent',
            type: 'bar'
        }

        var data = [attendance, total];
        var layout = {
            colorway: colorway_colors,
            barmode: 'relative',
            title: 'Subject Wise Class Attendance',
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
            values: [parseInt('<?php echo $presentp; ?>'), parseInt('<?php echo $absentp; ?>')],
            // values: [65, 35],
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

    show_bar();
    show_pie();
</script>

<?php
//$result = classLinePlot($conn,$subject_id);
?>
<script>
    function showtable() {
        if (tablecount % 2 === 0) {
            document.getElementById("tablebtn").innerHTML = "Hide Table";
            document.getElementById("showtable").style.display = "block";

            tablecount += 1;
        } else {
            document.getElementById("tablebtn").innerHTML = "Show Table";
            document.getElementById("showtable").style.display = "none";
            tablecount += 1;
        }

    }

    function linePlotGraph(dates, boys, girls, total) {

        var plotDiv = document.getElementById('plot');
        var traces = [{
                x: dates,
                y: total,
                //x: ['2013-10-04 22:23:00', '2013-11-04 22:23:00', '2013-12-04 22:23:00'],
                // y: [2, 1, 4],
                stackgroup: 'one',
                name: "Total"
            },
            {
                x: dates,
                y: boys,
                //x: ['2013-10-04 22:23:00', '2013-11-04 22:23:00', '2013-12-04 22:23:00'],
                //y: [1, 1, 2],
                stackgroup: 'one',
                name: "Boys"
            },
            {
                x: dates,
                y: girls,
                //x: ['2013-10-04 22:23:00', '2013-11-04 22:23:00', '2013-12-04 22:23:00', '2014-01-12', , '2014-02-12', '2014-03-12'],
                //y: [3, 0, 2, 5, 6, 7],
                stackgroup: 'one',
                name: "Girls"
            }
        ];

        var layout = [{
            title: 'Total Attendance',
            xaxis: {
                autotick: false,
                ticks: false
            }
            // autosize:true
        }];

        var config = {
            responsive: true
        };

        Plotly.newPlot('linePlot', traces, layout, config);
    }
    $(document).ready(function() {
            $('.select2').select2({
                placeholder: "Select An hour",
            });
        });
</script>