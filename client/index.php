<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('location: ./login/');
    exit;
}

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== false) {
    header('location: ./home/');
    exit;
}

?>