<?php
session_start();
session_unset();
header("Location: ../pages/Login/index.php");
session_destroy();
exit();
?>
