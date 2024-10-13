<?php
session_start();

// Not a customer go log in again
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'customer') {
    header('Location: login.php');
    exit();
}


?>