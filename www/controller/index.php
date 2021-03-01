<?php

require_once __DIR__ . '/../../vendor/autoload.php';

@session_start();

// check if logged in.
if (isset($_SESSION['log_in']) && $_SESSION['log_in']) {
    // user is student.
    if (isset($_SESSION['sid'])) {
        include __DIR__ . '/../view/sign.html';
    }
    // user is teacher.
    elseif (isset($_SESSION['tid'])) {
        include __DIR__ . '/../view/sign.html';
    }
}
// not logged in.
else {
    include __DIR__ . '/../view/sign.html';
}
