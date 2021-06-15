<?php
include_once 'includes/header.php';
$subject = $_GET["sub"];
$subject_id = $_GET["code"];
$result_array = classLinePlot_subject($conn, $subject_id);


$date_array = implode(",", $result_array[0]);

$boys_array = implode(",", $result_array[1]);

$girls_array = implode(",", $result_array[2]);

$total_array = implode(",", $result_array[3]);

?>

<script>
    var tablecount = 0;
</script>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.plot.ly/plotly-latest.min.js"></script>
    <style>
        #linePlot {
            width: 60%;
        }

        @media screen and (max-height: 680px) {
            #linePlot {
                width: 140%;
                margin-left: -50px;
            }
        }

        input[type="date"]:not(.has-value):before {
            color: gray;
            content: attr(placeholder);
        }
    </style>

    <!-- Warning Messages -->
    <?php
    if (!isset($_SESSION["uid"])) {
        header("Location: index.php?log=0");
    }
    ?>
    <?php
    if (isset($_GET["attd"])) {;
    ?>
        <br>
        <center>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                Attendance Marked Successfully!
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </center>
    <?php
    } ?>
    <style>
        body {
            font-family: "Lato", sans-serif;
        }

        input[type="date"]:not(.has-value):before {
            color: gray;
            content: attr(placeholder);
        }

        #cont {
            border-radius: 5px;
            background-color: gainsboro;
            transform: translate(0px, -20px);
        }

        .tablink {
            background-color: #d6eaf8;
            color: black;
            float: left;
            border: none;
            outline: none;
            cursor: pointer;
            padding: 14px 16px;
            font-size: 17px;
            width: 33.33%;
            font-weight: bold;
        }

        #firstcol {
            margin-left: 26%;
        }

        #edithour {
            width: 10%;
        }

        @media screen and (max-height: 680px) {
            .tablink {
                padding: 10px 0 10px 0;
                font-size: 10px;

            }

            #firstcol {
                margin-left: 0;
            }

            #edithour {
                width: 40%;
            }
        }

        /*  .tablink:hover {
            background-color: #ff4747;
        }*/

        /* Style the tab content */
        .tabcontent {
            color: black;
            display: none;
            padding: 50px;
            text-align: center;
        }

        .inner {
            background-color: white;
        }

        #past {
            background-color: white;
        }

        #edit {
            background-color: white;
        }

        #mark {
            background-color: white;
        }

        .rounds {
            border-radius: 20px;
        }

        tbody {
            background-color: white;
        }

        #showtable {
            display: none;
        }
    </style>
</head>

<body>
    <div id="cont" style="margin: 5%;">
        <button class="tablink btn btn-outline-info border border-info" onclick="openDiv('past', this)" id="defaultOpen">PAST ATTENDANCE</button>
        <button class="tablink  btn btn-outline-info border border-info" onclick="openDiv('mark', this)">MARK ATTENDANCE</button>
        <button id="editbtn" class="tablink  btn btn-outline-info border border-info" onclick="openDiv('edit', this)">EDIT ATTENDANCE</button>
        <script>
            var editbtn = document.getElementById("editbtn");
        </script>
        <div class="inner">
            <div id="past" class="tabcontent">
                <center>
                    <br><br>
                    <h4><?php echo $subject; ?></h4>
                    <div id="linePlot"></div>
                    <input type="date" id="table_date" name="Date" required placeholder="Date: "> <br><br>
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
                    <input type="button" id="table_go" value="Go" name="" class="btn btn-outline-info"><br><br>
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
                </center>
            </div>


            <center>
                <br>
                <div id="mark" class="tabcontent">
                    <div id="full-content">
                        <div class="row">
                            <div id="firstcol" class="column">
                                <a class="sub-anc" href="attendance.php?sub=<?PHP echo $subject_id ?>&subcode=<?php echo $_GET["sub"]?>">
                                    <div class="card rounds">
                                        <img src="img/attendance.png" alt="manual" style="width:100%">
                                        <div class="content">
                                            <h5 style="margin-top:10px;"><b>Mark attendance manually</b></h5>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <div class="column">
                                <a class="sub-anc" href="qr_code.php?sub=<?PHP echo $subject_id ?>">
                                    <div class="card rounds">
                                        <img src="img/qr-code.png" alt="Avatar" style="width:100%">
                                        <div class="content">
                                            <h5 style="margin-top:10px;"><b>Mark attendance using QR Code</b></h5>
                                            <!-- <p>CS1098</p> -->
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </center>

            <div id="edit" class="tabcontent">
                <form id="editform">
                    <input id="editdate" type="date" name="editdate" style="padding: 5px; border-radius:5px" placeholder="Date:&ensp;" required>
                    <br><br>
                    <!-- Hour: <select id="edithour" name="hour" style="padding: 10px; border-radius:5px" id="leave-type">
                        <option value="1">1</option>
                        <option value="2">2</option>
                    </select> -->
                    <div id="content">
                        <select id="edithour1" name="hour" style="width:20%" required>
                            <option value="" selected disabled>Select an hour</option>
                            <option value="1">First Hour : 1</option>
                            <option value="2">Second Hour : 2</option>
                            <option value="3">Third Hour : 3</option>
                            <option value="4">Fourth Hour : 4</option>
                            <option value="5">Fifth Hour : 5</option>
                            <option value="6">Sixth Hour : 6</option>
                        </select>
                        <br><br>
                    </div>
                    <br><br>
                    <input class="btn btn-outline-success" type="button" name="editsubmit" value="Go" onclick="getatt()" style=" width:20%">
                </form>
                <br><br>

                <div id="showatt" style="display: none;">
                    <div id="table" style="overflow:auto">
                        <table id="total_attendance_table1" class="table table-hover " style="margin:auto;display:none">
                        <thead>
                            <tr style="background-color:black;color:white">
                                <th scope="col">Register Number</th>
                                <th scope="col">Name</th>
                                <th scope="col">Attendance</th>
                            </tr>
                        </thead>
                        <tbody id="attendance_table1"></tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <script>
        var student_name = [];
        var register_number = [];
        var student_status = [];
        $(document).ready(function() {
            $("#table_go").click(function() {
                var date = document.getElementById("table_date").value;
                var hour = $("#edithour").select2().val();
                if (date.length == 0) {
                    alert("Enter date");
                }else if(hour == null){
                alert("Enter hour");
                 }
                 else {
                    $.ajax({
                        url: "includes/show_table.php",
                        type: "POST",
                        dataType: "text",
                        data: {
                            "date": date,
                            "subject_id": "<?php echo $subject_id; ?>",
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
            document.getElementById("attendance_table").innerHTML = "";
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
        function build_table1(register_number, student_name, student_status) {
            document.getElementById("attendance_table1").innerHTML = "";
            for (var i = 0; i < student_name.length; i++) {
                var print_status = '';
                if (student_status[i] == 1) {
                    var print_status = '<button class="btn btn-success" value="'+register_number[i]+'" onclick="editAttd(this)">Present</button>';
                } else {
                    var print_status = '<button class="btn btn-danger" value="'+register_number[i]+'" onclick="editAttd(this)">Absent</button>';
                }
                var row = `<tr>
                            <td>${register_number[i]}</td>
                            <td>${student_name[i]}</td>
                            <td>${print_status}</td></tr>`;

                document.getElementById("attendance_table1").innerHTML += row;
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
        //TODO PHP edithour
        function getatt() {

            var editdate = document.getElementById("editdate");
            var edithour = $("#edithour1").select2().val();

            var form = document.forms['editform'];
            if (form["editdate"].value == "") {
                alert("Please enter a date!");
            } else if (edithour == null) {
                alert("Please enter a hour!");
            } else {

                $.ajax({
                    url: 'includes/show_table.php',
                    type: 'POST',
                    data: jQuery.param({
                        "date": editdate.value,
                        "subject_id": "<?php echo $subject_id ?>",
                        "hour": edithour.toString()
                        
                    }),
                    contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                    success: function(result) {
                    try{
                        $("#editform").children().prop("disabled",true);
                        $("#edithour1").prop("disabled",true);
                        //console.log(result);
                        result = JSON.parse(result);

                        student_name = result["name"].slice(1, -1).split(",");
                        register_number = result["reg_num"].slice(1, -1).split(",");
                        student_status = result["attendance"].slice(1, -1).split(",");
                        document.getElementById("total_attendance_table1").style.display = "block";
                        document.getElementById("attendance_table1").innerHTML = "";

                        build_table1(register_number, student_name, student_status);
                    }
                     catch(error){
                         //console.log(error);
                         $("#editform").children().prop("disabled",false);
                         $("#edithour1").prop("disabled",false);
                        alert("Enter correct date/hour");
                     }   
                    },
                    
                    error: function() {
                        alert("Internal Error. Please Try again later2!");
                    }
                });
                if (false) {
                    alert("No class available in the specified date and hour!");
                    editdate.value = "";
                    edithour.value = "";
                    return;
                }
                document.getElementById("showatt").style.display = "block";
            }
        }

        //TODO PHP
        function editAttd(btn) {
            console.log(btn.value);
            var editdate = document.getElementById("editdate");
            var edithour = $("#edithour1").select2().val();
            if (btn.innerHTML == "Present") {
                var value = confirm("Do you want to change to Absent");
                // console.log(value);
                if (value) {
                    $.ajax({
                        url: "includes/editget_table.inc.php",
                        type: "POST",
                        dataType: "text",
                        data: {
                            "regno": btn.value,
                            "date": editdate.value,
                            "hour": edithour.toString(),
                            "subject_id": "<?php echo $subject_id?>",
                            "tostatus": "0"
                        },
                        success:function(result)
                        {
                            if(result==1)
                            {
                                btn.innerHTML = "Absent";
                                btn.classList.remove("btn-success");
                                btn.classList.add("btn-danger");
                                alert("Successfully Updated Attendance");
                            }
                            else{
                                console.log(result);
                                alert("SERVER ERROR!");
                            }
                        },
                        error:function(error)
                        {
                            alert(error);
                        }
                    });
                }

            } else if (btn.innerHTML == "Absent") {
                var value = confirm("Do you want to change to Present");
                if (value) {
                    $.ajax({
                        url: "includes/editget_table.inc.php",
                        type: "POST",
                        dataType: "text",
                        data: {
                            "regno": btn.value,
                            "date": editdate.value,
                            "hour": edithour.toString(),
                            "subject_id": "<?php echo $subject_id?>",
                            "tostatus": "1"
                        },
                        success:function(result)
                        {
                            if(result==1)
                            {
                                btn.innerHTML = "Present";
                                btn.classList.remove("btn-danger");
                                btn.classList.add("btn-success");
                                alert("Successfully Updated Attendance");
                            }
                            else{
                                alert("SERVER ERROR!");
                            }
                        },
                        error:function(error)
                        {
                            alert(error);
                        }
                    });
                }
            }
        }

        function openDiv(cityName, elmnt) {
            var i, tabcontent, tablinks;
            tabcontent = document.getElementsByClassName("tabcontent");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
            }
            tablinks = document.getElementsByClassName("tablink");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].style.backgroundColor = "";
            }
            document.getElementById(cityName).style.display = "block";
            elmnt.style.backgroundColor = "#5dc0de";
            elmnt.style.color = "black";
        }
        // Get the element with id="defaultOpen" and click on it
        document.getElementById("defaultOpen").click();


        var dates = '<?php echo $date_array; ?>'.split(",");
        var boys = '<?php echo $boys_array; ?>'.split(",");
        var girls = '<?php echo $girls_array; ?>'.split(",");
        var total = '<?php echo $total_array; ?>'.split(",");

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
                // x: ['2013-10-04 22:23:00', '2013-11-04 22:23:00', '2013-12-04 22:23:00'],
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
            // autosize:true
        }];

        var config = {
            responsive: true
        };

        Plotly.newPlot('linePlot', traces, layout, config);


        $(document).ready(function() {
            $('.select2').select2({
                placeholder: "Select An hour",
            });
            $('#edithour1').select2({
                placeholder: "Select An hour",
            });
        });
    </script>
    <?php include_once 'includes/footer.php' ?>
</body>

</html>