<?php
include_once 'includes/header.php';
//echo $_GET["a"];
$subject = $_GET["sub"];

?>

<br>

<!-- Warning Messages -->

<?php
if (!isset($_SESSION["uid"])) {
    header("Location: index.php?log=0");
}
if(!checkAttEntry($conn,$subject))
    {
        header("Location: $dashboard?h=0");
        exit();
    }
?>
<?php
$result = manualAttendanceLeft($conn, $subject);
if ($result == null) {
    header("Location: $dashboard?h=0");
    exit();
} else {
    $hour = $result[1];
?>
<center>
    <div id="content">
        <select id="hour" class="select2" multiple style="width:30%">
        <?php
                                    for ($i = 0; $i < count($hour); $i += 1) {
                                        echo "<option value='" . (int)$hour[$i] . "'>HOUR: " . (int)$hour[$i] . "</option>";
                                    }
                                    ?>
        </select>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="checkbox_hour" id="checkbox_hour">
            <label class="form-check-label" for="checkbox_hour">
                Select All
            </label>
        </div>
        <div class="container m-2">
            <button id="qr_show" onclick="qrcode()" class="btn btn-outline-success">Generate QR Code</button>
            <button id="stop" onclick="endQr()" class="btn btn-outline-danger" style="display: none;">Stop and Show Attendance</button>
        </div>
        <br>

        <div>
            <img id="img_qr" src="" alt="" style="display: none;">
        </div>
        <div class="container card" id="table" style="display:none">
        <div class="container">
            <div class="d-flex justify-content-center align-items-center">
                <table id="total_attendance_table" class="table table-hover " style="margin:auto;">
                    <thead>
                        <tr style="background-color:black;color:white">
                            <th scope="col">Register Number</th>
                            <th scope="col">Name</th>
                            <th scope="col">Attendance</th>
                        </tr>
                    </thead>
                    <tbody id="attendance_table">
                        <?php
                            echo '<tr style="background-color:white;color:black">
                            <th scope="col">715518104007</th>
                            <th scope="col">bala</th>
                            <th scope="col"><button class="btn btn-success" style="cursor:pointer">Present</button></th>
                        </tr>';
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
        </div>
    </div>
</center>


<script>
    $(document).ready(function() {
        $('.select2').select2({
            placeholder: 'Select Hours',
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
    });

    function endQr() {
        
        var selected = $("#hour").select2("val");
        if (selected.length == 0) {
            alert("Please Select atleast one hour!")
        } else {
            document.getElementById("stop").style.display = "none";
        document.getElementById("table").style.display = "block";
        var img1 = document.getElementById("img_qr");

        img1.style.display = "none";
        }
        //content.parentElement.removeChild(img1);
    }

    function qrcode() {
        
        var selected = $("#hour").select2("val");
        if (selected.length == 0) {
            alert("Please Select atleast one hour!")
        } else {
            document.getElementById("qr_show").style.display = "none";
        document.getElementById("stop").style.display = "block";
            var content = document.getElementById("content");
            var hours = selected.join();
            // if (selected.length == 1) {
            //     hours = selected[0];
            // } else {
            //     for (var i = 0; i < selected.length; i++) {
            //         hours += selected[i] + ",";
            //     }
            // }
            var img = document.getElementById("img_qr");
          //  hours = hours.slice(0, hours.length - 1);
            var qr = img;
            var subject = "<?php echo $subject ?>";
            console.log(hours);
            img.style.display = "block";
            var url = "https://chart.googleapis.com/chart?chs=500x500&cht=qr&chl=http%3A%2F%2Flocalhost%2Fdb_server%2Fqr_attendance.php%3fsub=" + subject + "%26h=" + hours + "%26date=<?php echo date("Y-m-d"); ?>" +"&choe=UTF-8";
            console.log(url);
            qr.setAttribute("id", "qrcode_img");
            qr.setAttribute("src", url);
            qr.setAttribute("id", "img_qr");
            qr.setAttribute("alt", "QR Code");
            content.appendChild(qr);
        }
    }
</script>
</body>

</html>

<?php } include_once 'includes/footer.php'; ?>

</body>

</html>