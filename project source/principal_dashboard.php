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
    <h2 style="text-align:center">PSG Institute of Technology and Applied Research</h2>

    </a> <br><br>

    <?php
    $subject = "C++"; //Queried from DB
    $sub_code = "CS1234";
    $class_num = 1;
    $class_count = 4;
    ?>
    <div class="row">
        <div class="column">
            <a id='class-1' class="sub-anc" href="#class-1" onclick="openSub('First Year')" style="cursor:pointer">
                <div class="card" style="border-radius:10px">
                    <center><img src="img/graduation-1.png" alt="Avatar" style="width:70%;border-radius:10px"></center>
                    <div class="content">
                        <h4><b>First Year</b></h4>
                    </div>
                </div>
            </a>
        </div>

        <div class="column">
            <a id='class-2' class="sub-anc" href="#class-2" onclick="openSub('Second Year')" style="cursor:pointer">
                <div class="card" style="border-radius:10px">
                    <center><img src="img/graduation-2.png" alt="Avatar" style="width:70%;border-radius:10px"></center>
                    <div class="content">
                        <h4><b>Second Year</b></h4>
                    </div>
                </div>
            </a>
        </div>

        <div class="column">
            <a id='class-2' class="sub-anc" href="#class-2" onclick="openSub('Third Year')" style="cursor:pointer">
                <div class="card" style="border-radius:10px">
                    <center><img src="img/graduation-3.png" alt="Avatar" style="width:70%;border-radius:10px"></center>
                    <div class="content">
                        <h4><b>Third Year</b></h4>
                    </div>
                </div>
            </a>
        </div>

        <div class="column">
            <a id='class-2' class="sub-anc" href="#class-2" onclick="openSub('Fourth Year')" style="cursor:pointer">
                <div class="card" style="border-radius:10px">
                    <center><img src="img/graduation-4.png" alt="Avatar" style="width:70%;border-radius:10px"></center>
                    <div class="content">
                        <h4><b>Fourth Year</b></h4>
                    </div>
                </div>
            </a>
        </div>
    </div>


    <div id="buttons" class="container card" style="display:none">
        <h3 id="dept"></h3>
        <div class="row">
            <div class="col">
                <a href="#buttons"><button class="btn btn-warning p-3 m-1" style="width:100%" onclick="showClass('CSE')">CSE</button></a>
            </div>
            <div class="col">
                <a href="#buttons"><button class="btn btn-warning p-3 m-1" style="width:100%" onclick="showClass('CIVIL')">CIVIL</button></a>

            </div>
            <div class="col">
                <a href="#buttons"><button class="btn btn-warning p-3 m-1" style="width:100%" onclick="showClass('ECE')">ECE</button></a>

            </div>
            <div class="col">
                <a href="#buttons"><button class="btn btn-warning p-3 m-1" style="width:100%" onclick="showClass('EEE')">EEE</button></a>

            </div>
            <div class="col">
                <a href="#buttons"><button class="btn btn-warning p-3 m-1" style="width:100%;" onclick="showClass('MECH')">MECH</button></a>

            </div>
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
    function openSub(year) {
        document.getElementById("buttons").style.display = "block";
        document.getElementById("yearplot").style.display = "none";
        document.getElementById('dept').innerHTML = year;

    }

    function showClass(dept) {
        document.getElementById("yearplot").style.display = "block";
        document.getElementById('year-name').innerHTML = dept;

        
    }
</script>

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
            // height: 400,
            // width: 700,
            // autosize: true
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

            // height: 400,
            // width: 700,
            // autosize: true
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
</script>