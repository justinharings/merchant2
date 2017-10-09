<?php
session_start();

$_SESSION['print_file'] = $_GET['print_file'];

if (!isset($_SESSION['accessToken'])) {
    
    header('Location: oAuthRedirect.php?op=getauth');
}
else {
    header("Location: example.php");
}
?>