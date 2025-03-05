<?php
$title = "Dashboard Page";
$page = "dashboard";
include($_SERVER['DOCUMENT_ROOT']."/booking-system/admin/layouts/header.php");


?>

<?php if (isset($message)) echo "<div class='alert alert-info mt-3'>$message</div>"; ?>

<?php include($_SERVER['DOCUMENT_ROOT']."/booking-system/admin/layouts/footer.php");?>