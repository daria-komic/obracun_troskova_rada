<?php 
    session_start();
    unset($_SESSION['korime']);
    unset($_SESSION['uloga']);
    unset($_SESSION['ime']);
    session_destroy();
    header('Location: index.php');
?>