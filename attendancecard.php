<?php
$title = "attcard";
require "head.php";
?>

<?php
require "header.php";
?>

<div class="row g-3 align-items-center">
    <div class="col-auto">
        <label for="inputPassword6" class="col-form-label me-5">hello</label>
    </div>
    <div class="col-auto">
        <input type="radio" class="btn-check" name="present" id="present" autocomplete="off">
        <label class="btn btn-outline-success" for="present">Présent</label>
    </div>
    <div class="col-auto">
        <input type="radio" class="btn-check" name="late" id="late" autocomplete="off">
        <label class="btn btn-outline-warning" for="late">En Retard</label>
    </div>
    <div class="col-auto">
        <input type="radio" class="btn-check" name="absent" id="absent" autocomplete="off">
        <label class="btn btn-outline-danger" for="absent">Absent</label>
    </div>
</div>

<?php
require "footer.php";
?>