<?php

@session_start();
@session_regenerate_id(true);

if (isset($_SESSION['log_in']) && $_SESSION['log_in']) {
    if (isset($_SESSION['sid'])) {
        include 'view/student.html';
    } else if (isset($_SESSION['tid'])) {
        include 'view/teacher.html';
    }
} else {
    include 'view/sign.html';
}
