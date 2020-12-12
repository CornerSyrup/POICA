<?php

/**
 * sign in process with student id and password.
 * 
 * take POST data:
 * sid:     student id of user.
 * pwd:     password of user.
 * 
 * use session data:
 * user:    user id.
 * log_in:  is logged in.
 */

namespace controller;

require_once 'model/Authentication.php';
require_once 'model/Global.php';
require_once 'model/Logger.php';
require_once 'model/Localizer.php';
require_once 'model/Validation.php';

use model\authentication as auth;
use model\validation as valid;

// clear previous login status
session_start();
session_regenerate_id(true);

$logger = new \model\Logger('form', 'signin');
$view = 'signin';
$errmsg = '';

try {
    // repel http request method
    if (strtoupper($_SERVER['REQUEST_METHOD']) != 'POST') {
        throw new \RequestMethodException('POST', strtoupper($_SERVER['REQUEST_METHOD']));
    }

    // localize input
    $_POST = \model\Localizer::LocalizeArray($_POST);

    // valid input
    if (!valid\validate_signin_form($_POST['sid'], $_POST['pwd'])) {
        throw new valid\ValidationException('Sign in form data is invalid.');
    }

    // auth success
    if (auth\authenticate_form($_POST['sid'], $_POST['pwd'])) {
        $_SESSION['user'] = $_POST['sid'];
        $_SESSION['log_in'] = true;
        $logger->appendRecord("[{$_POST['sid']}] logged in successfully from form.");
        $view = 'signin';
    }
    // auth fail
    else {
        $logger->appendRecord("[{$_POST['sid']}] attempted but fail to login from form.");
        $view = 'signin';
    }
} catch (\RequestMethodException $re) {
    // inappropriate request method
    $logger->appendError($re);
    $errmsg = '';
    $view = 'signin';
} catch (valid\ValidationException $ve) {
    // invalid input
    $logger->appendError($ve);
    $errmsg = 'Please check your input and try again.';
    $view = 'signin';
} catch (auth\AuthenticationException $ae) {
    // no registration found
    $logger->appendError($ae);
    $errmsg = "Account not found, please <a href=\"/signup/\">create a new account</a>.";
} catch (\Throwable $th) {
    $logger->appendError($th);
    $view = 'signin';
}

ob_start();
include "view/{$view}.php";
ob_end_flush();
