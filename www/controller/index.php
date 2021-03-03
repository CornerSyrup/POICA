<?php

require_once __DIR__ . '/../../vendor/autoload.php';

@session_start();

ob_start();

// check if logged in.
if (isset($_SESSION['log_in']) && $_SESSION['log_in']) {
    // user is student.
    if (isset($_SESSION['sid']))
        include __DIR__ . '/../view/student.html';
    // user is teacher.
    elseif (isset($_SESSION['tid']))
        include __DIR__ . '/../view/teacher.html';
}
// not logged in.
else
    include __DIR__ . '/../view/sign.html';

ob_end_flush();

exit;
