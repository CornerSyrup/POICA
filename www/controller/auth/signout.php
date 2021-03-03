<?php

require_once __DIR__ . '/../../../vendor/autoload.php';

use POICA\authentication\Authenticator;
use POICA\model\Logger;

@session_start();

if (isset($_SESSION['user'])) {
    $msg = "User [{$_SESSION['user']}]";
} elseif (isset($_SESSION['tid'])) {
    $msg = "Teacher [{$_SESSION['tid']}]";
} else {
    $msg = '';
}

Authenticator::sign_out();

Logger::append(
    $msg . ' has been signed out successfully.',
    __DIR__ . '/../../logs/auth.log'
);

include __DIR__ . '/../../view/sign.html';

session_regenerate_id();
