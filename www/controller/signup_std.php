<?php

/**
 * standard sign up process with student id and password
 * 
 * take POST data:
 * sid: student id of user
 * pwd: password of user
 * jfn: japanese first name
 * jln: japanese last name
 * jfk: first name kana
 * jlk: last name kana
 */

namespace controller;

require_once 'model/Authentication.php';
require_once 'model/Localizer.php';
require_once 'model/Logger.php';
require_once 'model/Validation.php';

use model\authentication as auth;
use model\validation as valid;

$logger = new \model\Logger('Sign up (form)', 'signup');
$view = 'signup_form';
$errmsg = '';

try {
    // repel http request method
    if ($_SERVER['REQUEST_METHOD'] != 'POST') {
        throw new \RequestMethodException('POST', $_SERVER['REQUEST_METHOD']);
    }

    // localize input
    $_POST = \model\Localizer::LocalizeArray($_POST);

    // valid input
    if (!valid\validate_signup_form($_POST)) {
        throw new valid\ValidationException('Sign up form data is invalid.');
    }

    // enrol success
    if (auth\enrol($_POST)) {
        $logger->appendRecord("Success to enrol user with student id [{$_POST['sid']}]");
    }
    // enrol fail
    else {
        $logger->appendRecord("Fail to enrol user with student id [{$_POST['sid']}]");
    }
} catch (\RequestMethodException $re) {
    // inappropriate request method
    $logger->appendError($re);
    $errmsg = '';
} catch (valid\ValidationException $ve) {
    // invalid input
    $logger->appendError($ve);
    $errmsg = 'Please check your input and try again.';
} catch (\Throwable $th) {
    $logger->appendError($th);
}

ob_start();
include "view/{$view}.php";
ob_end_flush();
