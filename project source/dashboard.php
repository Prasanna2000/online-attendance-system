<?php include_once 'includes/header.php' ?>

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

<!--USING BOOTSTRAP 4.0.0-->

<!-- TODO PHP -->

<!--cards-->
<?php
$result  = subjectsList($conn);
if ($result == null) {
  header("Location: index.php?log=1");
} else if ($result != null) {
?>
  <div id="full-content">
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


<?php include_once 'includes/footer.php' ?>

</body>

</html>