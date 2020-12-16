<?php

/**
 * sign in process with student id and password.
 * 
 * take POST data:
 * sid:     student id of user.
 * pwd:     password of user.
 * 
 * use session data:
 * user:    student id.
 * log_in:  is logged in.
 * 
 * respond in json:
 * status:  1 for logged in, 0 for logged out.
 * error:   `message` for error message, `code` for error code.
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
/**
 * Whether request is using fetch api.
 */
$fetchApi = false;
$fetchRes;
$view = 'signin';
$errmsg = '';

try {
    // normal get sign in page
    if (strtoupper($_SERVER['REQUEST_METHOD']) == 'GET') {
        ob_start();
        include "view/{$view}.php";
        ob_end_flush();
        exit;
    }

    // repel http request method
    if (strtoupper($_SERVER['REQUEST_METHOD']) != 'POST') {
        throw new \RequestMethodException('POST', strtoupper($_SERVER['REQUEST_METHOD']));
    }

    // accept js fetch api
    if (empty($_POST) && strtolower($_SERVER["CONTENT_TYPE"]) == 'application/json') {
        $_POST = json_decode(file_get_contents('php://input'), true);
        $fetchApi = true;
        $fetchRes = [];
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
        $logger->appendRecord("[{$_POST['sid']}] logged in successfully.");
        if ($fetchApi) {
            $fetchRes['status'] = 1;
        } else {
            $view = 'landing';
        }
    }
    // auth fail
    else {
        $logger->appendRecord("[{$_POST['sid']}] attempted but fail to login.");
        if ($fetchApi) {
            $fetchRes['status'] = 0;
        } else {
            $view = 'signin';
        }
    }
} catch (\RequestMethodException $re) {
    // inappropriate request method
    $logger->appendError($re);
    if ($fetchApi) {
        $fetchRes['error'] = [
            'error' => '',
            'code' => 1
        ];
    } else {
        $errmsg = '';
        $view = 'signin';
    }
} catch (valid\ValidationException $ve) {
    // invalid input
    $logger->appendError($ve);
    if ($fetchApi) {
        $fetchRes['error'] = [
            'message' => 'Please check your input and try again.',
            'code' => 2
        ];
    } else {
        $errmsg = 'Please check your input and try again.';
        $view = 'signin';
    }
} catch (auth\AuthenticationException $ae) {
    // no registration found
    $logger->appendError($ae);
    if ($fetchApi) {
        $fetchRes['error'] = [
            'message' => "Account not found, please <a href=\"/signup/\">create a new account</a>.",
            'code' => 3
        ];
    } else {
        $errmsg = "Account not found, please <a href=\"/signup/\">create a new account</a>.";
    }
} catch (\Throwable $th) {
    $logger->appendError($th);
    $view = 'signin';
}

// fetch api output, as json
if ($fetchApi) {
    header("Content-Type: application/json");
    echo json_encode($fetchRes);
}
// default output, as html page.
else {
    ob_start();
    include "view/{$view}.php";
    ob_end_flush();
}
