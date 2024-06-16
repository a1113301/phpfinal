<?php
session_start();
$_SESSION['adminlog'] = false;


header("Location: ../index.php");
exit();
?>
