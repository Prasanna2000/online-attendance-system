<?php
include_once 'includes/header.php';
$subject = $_GET["sub"];
$h=0;
$attd = 0;
?>

<style>
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

    .vertical-ruling {
        border-left: 2px solid black;
    }

    #students,
    #hour,
    #hour1 {
        width: 400px;
    }

    @media screen and (max-height: 680px) {
        .vertical-ruling {
            border-left: 0px;
        }

        #students,
        #hour,
        #hour1 {
            width: 300px;
        }
    }
</style>

<!-- IF PAGE LOAD HAS THE PARAMETERS FOR HOUR and CONFIRM == 0 in db, then disable the lhs of the page and alert to conform the attendance-->
<!-- In above condition, alert if page is left without confirm button -->
<!-- if confirm==1 in db redirect to subject page with error message -->

<br>
<!-- Warning Messages -->
<?php
if (!isset($_SESSION["uid"])) {
    header("Location: index.php?log=0");
    exit();
}
if(!isset($_GET["h"]))
{
    if(!checkAttEntry($conn,$subject))
    {
        header("Location: $dashboard?h=0");
        exit();
    }
}

?>

<?php
$result = manualAttendanceLeft($conn, $subject);
if ($result == null) {
    header("Location: $dashboard?h=0");
    exit();
} else {
    $students = $result[0];
    $hour = $result[1];
?>
    <div class="m-1">
        <div class="container bg-light p-3">
            <div class="row">
                <div id="left" class="col-md">
                    <div class="container">
                        <div class="row">
                            <form action="includes/mark_attd.inc.php?sub=<?php echo $subject ?>&subcode=<?php echo $_GET["subcode"]?>" method="post">
                                <select id="hour" name="hour[]" multiple="true" required>
                                    <?php
                                    for ($i = 0; $i < count($hour); $i += 1) {
                                        echo "<option value='" . (int)$hour[$i] . "'>HOUR: " . (int)$hour[$i] . "</option>";
                                    }
                                    ?>

                                </select><br><br>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="checkbox_hour" id="checkbox_hour">
                                    <label class="form-check-label" for="checkbox_hour">
                                        Select All
                                    </label>
                                </div> <br>
                                <input value="Present" name="attd_type" type="hidden" id="hidden_attd">
                                <input type="button" id="attd-type" class="btn btn-success" value="Present" onclick="changeAttdType(this)" data-toggle="tooltip" title="Click to toggle attendance type">
                                <br><br>
                                <select id="students" name="students[]" multiple="true" required>
                                    <?php
                                    foreach ($students as $reg_no => $name) {
                                        echo "<option value='" . $reg_no . "'>" . $name . " : " . $reg_no . "</option>";
                                    }
                                    ?>
                                </select><br><br>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="checkbox" id="checkbox">
                                    <label class="form-check-label" for="checkbox">
                                        Select All
                                    </label>
                                </div> <br>

                                <input class="btn custom-btn-primary" type="submit" value="Mark Attendance"><br><br>
                            </form>
                        </div>
                    </div>
                </div>
                <div id="right" class="col-md vertical-ruling">
                    <div id="table" style="overflow:auto">
                        <center>
                            <select id="hour1" name="hour1[]" multiple="true" required>
                                <?php
                                for ($i = 0; $i < count($hour); $i += 1) {
                                    echo "<option value='" . (int)$hour[$i] . "'>HOUR: " . (int)$hour[$i] . "</option>";
                                }
                                ?>
                            </select><br>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="checkbox" id="checkbox_hour_1">
                                <label class="form-check-label" for="checkbox_hour_1">
                                    Select All
                                </label>
                            </div> <br>
                            <table class="table table-hover table-bordered" style=" margin:auto;width: 40%;">
                                <thead>
                                    <tr style="background-color:black;color:white">
                                        <th scope="col">Roll Number</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = 1;
                                    foreach ($students as $reg_no => $name) { ?>
                                        <!-- echo "<option value='" . $reg_no . "'>" . $name . " : " . $reg_no . "</option>"; -->
                                        <tr>
                                            <th id="r_<?php echo $reg_no; ?>_roll" scope="row"><?php echo $reg_no; ?></th>
                                            <td><?php echo $name;?></td>
                                            <input id="stud_roll" type="hidden" name="stud_roll" value=<?php echo $reg_no; ?>>
                                            <td id="r_<?php echo $reg_no; ?>"><button class="btn btn-danger" value=<?php echo $reg_no?> onclick="presentEdit(this,'<?php echo $reg_no; ?>')">Absent</button></td>
                                        </tr>
                                    <?php $i += 1;
                                    }
                                    ?>
                                </tbody>
                            </table> <br>
                            <form action="includes/confirm_attd.inc.php?sub=<?php echo $subject ?>&subcode=<?php echo $_GET["subcode"]?>" method="post" onsubmit="return doConfirm()">
                                <input id="hours_confirm" type="hidden" name="hours_confirm">
                                <input id="mark_all" type="hidden" name="present">
                                <?php 
                                    if (isset($_GET["h"])) { 
                                        $edit = $_GET["h"];
                                    }
                                    else{
                                        $edit = 0;
                                    }
                                ?>
                                <input id="edit_confirm" type="hidden" name="edit_confirm" value=<?php echo $edit; ?> >
                                <input id="confirm" class="btn custom-btn-primary" type="submit" name="confirm" value="Mark Attendance"><br><br>
                            </form>
                    </div>
                </div>
                </center>
            </div>
        </div>
    </div>
<?php } ?>

<?php include_once 'includes/footer.php' ?>
</body>

</html>

<script>
    var called_present_edit = 0;
    var select_all = 0;
    $(document).ready(function() {
        $('#students').select2({
            placeholder: "Select Students"
        });

        $('#hour').select2({
            placeholder: "Select Hours"
        });

        $('#hour1').select2({
            placeholder: "Select Hours"
        });

        //Select All
        $("#checkbox").click(function() {
            if ($("#checkbox").is(':checked')) {
                $("#students > option").prop("selected", true); // Select All Options
                $("#students").trigger("change"); // Trigger change to select 2
            } else {
                $("#students > option").prop("selected", false);
                $("#students").trigger("change"); // Trigger change to select 2
            }
        });

        $("#checkbox_hour").click(function() {
            if ($("#checkbox_hour").is(':checked')) {
                $("#hour > option").prop("selected", true); // Select All Options
                $("#hour").trigger("change"); // Trigger change to select 2
            } else {
                $("#hour > option").prop("selected", false);
                $("#hour").trigger("change"); // Trigger change to select 2
            }
        });
        $("#checkbox_hour_1").click(function() {
            if ($("#checkbox_hour_1").is(':checked')) {
                $("#hour1 > option").prop("selected", true); // Select All Options
                $("#hour1").trigger("change"); // Trigger change to select 2
            } else {
                $("#hour1 > option").prop("selected", false);
                $("#hour1").trigger("change"); // Trigger change to select 2
            }
        });
    });



    //TO Get input from mark_attd.inc.php
    </script>
    <?php if (isset($_GET["h"])) { 
        $t=$_GET["t"];
        $h=$_GET["h"];
        $result=getBackAttendance($conn,$t,$subject,$_GET["h"]);
        foreach($result as $regno=>$status)
        {
            //echo $status;
            if($status=="1")
            {?>
            <script>
                var roll = "<?php echo $regno;?>";
               // console.log(roll);
                document.getElementById("r_<?php echo $regno;?>").innerHTML = "Present";
                document.getElementById("r_<?php echo $regno;?>").classList.remove("btn-danger");
                document.getElementById("r_<?php echo $regno;?>").classList.add("btn");
                document.getElementById("r_<?php echo $regno;?>").classList.add("btn-success");
                
                document.getElementById("r_<?php echo $regno;?>").setAttribute("onclick",'presentEdit(this,'+roll+')');
                document.getElementById("r_<?php echo $regno;?>").setAttribute("value","<?php echo $regno;?>");
                
            </script>
            <?php } 

        }
        ?>
        <script>
        var hours_back = '<?php echo $_GET["h"];  ?>'.split(",");
        $('#hour1').val(hours_back).trigger('change');

        document.getElementById("confirm").value = "Confirm";
        document.getElementById("confirm").classList.remove("custom-btn-primary");
        document.getElementById("confirm").classList.add("btn-warning");
        called_present_edit += 1;

        $("#left *").prop('disabled', true);

        </script>
    <?php } ?>
    <script>
    function changeAttdType(button) {
        if (button.value == "Present") {
            button.value = "Absent";
            document.getElementById("hidden_attd").value = "Absent";
            button.classList.remove("btn-success");
            button.classList.add("btn-danger");
        } else if (button.value == "Absent") {
            button.value = "Present";
            document.getElementById("hidden_attd").value = "Present";
            button.classList.remove("btn-danger");
            button.classList.add("btn-success");
        }

    }

    //TODO PHP
    function presentEdit(btn,roll_no) {
        console.log(roll_no);
        called_present_edit += 1;
        //$_GET['h']
        var hour = $("#hour1").select2('val');
        if (hour.length == 0) {
            alert("Please select atleast one hour");
            return;
        }

        if (!confirm("Do you want to change this attendance?")) {
            return;
        }

        //var roll_no = btn.value;
        //var roll_no = document.getElementById("stud_roll").value ;
        var status_stud;
        if (btn.innerHTML == "Present") {
            status_stud = "0";
        } else if (btn.innerHTML == "Absent") {
            status_stud = "1";
        }
        console.log("<?php echo $h?>",hour.toString(),roll_no,status_stud);
        $.ajax({
            url: 'includes/confirm_attd.inc.php?sub=<?php echo $subject ?>',
            type: 'POST',
            data: jQuery.param({
                "edit": "<?php echo $h?>",
                "hours": hour.toString(),
                "id": roll_no,
                "status": status_stud
            }),
            contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
            success: function(response) {
                if (response == 1) {
                    alert("Attendance updated successfully!");
                    if (btn.innerHTML == "Present") {
                        btn.innerHTML = "Absent";
                        btn.classList.remove("btn-success");
                        btn.classList.add("btn-danger");

                    } else if (btn.innerHTML == "Absent") {
                        btn.innerHTML = "Present";
                        btn.classList.remove("btn-danger");
                        btn.classList.add("btn-success");
                    }
                } else {
                    console.log(response);
                    alert(response);
                   // alert("Internal Error. Please Try again later1!");
                }
            },
            error: function() {
                alert("Internal Error. Please Try again later2!");
            }
        });
    }

    function doConfirm() {
        if (called_present_edit == 0) {
            if (confirm("Do you cant to mark all as absent?")) {
                var hour = $("#hour1").select2('val');
                document.getElementById("hours_confirm").value = hour.join();
                document.getElementById("mark_all").value = "1";
            }
        }
        else if(called_present_edit != 0){
            var hour = $("#hour1").select2('val');
            document.getElementById("hours_confirm").value = hour.join();
            document.getElementById("mark_all").value = "0";
        }
        document.getElementById("mark_all").value = "0";
        //TODO else alert that they have updated few record so cant select all

        if (hour.length == 0) {
            alert("Please select atleast one hour");
            return false;
        }
    }
</script>