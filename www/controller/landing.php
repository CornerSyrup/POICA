<?php


session_start();
session_regenerate_id();

if (!$_SESSION['user'] || !$_SESSION['log_in']) {
    header("Location: http://{$_SERVER['HTTP_HOST']}/signin/");
}

include 'view/landing.php';
