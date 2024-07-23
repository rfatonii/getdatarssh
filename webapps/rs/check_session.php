<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['ses_admin_login']) || $_SESSION['ses_admin_login'] != USERHYBRIDWEB.PASHYBRIDWEB) {
    header("Location: index.php");
    exit();
}
?>

