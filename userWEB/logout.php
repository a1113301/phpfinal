<?php
session_start();
$_SESSION['userlog'] = false;
$_SESSION['username'] = "";

header("Location: ../index.php");
exit();
?>
