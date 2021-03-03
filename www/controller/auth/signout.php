<?php

require_once __DIR__ . '/../../../vendor/autoload.php';

use POICA\model\Logger;

@session_start();

$logger = new Logger('off', 'auth');
$msg = '';

if (isset($_SESSION['user'])) {
    $msg = "User [{$_SESSION['user']}]";
    unset($_SESSION['user']);
    unset($_SESSION['sid']);
} elseif (isset($_SESSION['tid'])) {
    $msg = "Teacher [{$_SESSION['tid']}]";
    unset($_SESSION['tid']);
}

unset($_SESSION['log_in']);

$logger->append_record(
    $msg . ' has been signed out successfully.'
);

include __DIR__ . '/../../view/sign.html';

session_regenerate_id();
