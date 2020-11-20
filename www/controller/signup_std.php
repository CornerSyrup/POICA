<?php

/**
 * standard sign up process with student id and password
 */

namespace controller;

use model\Authenticator;
use model\Validation as valid;

require_once 'model/Authenticator.php';
require_once 'model/Localizer.php';
require_once 'model/Logger.php';
require_once 'model/Validator.php';

$logger = new \model\Logger('auth');
$view = 'signup_form';
$errmsg = '';

try {
    if (!valid\validate_signup_form($_POST)) {
        throw new \Exception("`{$_POST['sid']}` attempt to sign up, but fail on validation");
    }

    if (Authenticator::enrolment($_POST)){
        $logger->appendRecord("success of enrolment");
    } else {
        http_response_code(400);
    }
} catch (\Throwable $th) {
    http_response_code(500);
    $logger->appendError($th);
} finally {
    include "view/{$view}.php";
}
