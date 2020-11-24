<?php

/**
 * sign in process with suica card.
 * 
 * take POST data:
 * sid: student id of user.
 * idm: registered suica card idm code.
 * 
 * use session data:
 * user: user id.
 * log_in: is logged in.
 * 
 * respond:
 * status: 1 for logged in, 0 for logged out.
 * error: `message` for error message, `code` for error code.
 */

namespace controller;

require_once 'model/DBAdaptor.php';
require_once 'model/Localizer.php';
require_once 'model/Logger.php';
require_once 'model/Validation.php';

use model;
use model\authentication as auth;
use model\validation as valid;

session_start();
session_destroy();

$logger = new \model\Logger('Sign in (suica)');
$_POST = json_decode(file_get_contents('php://input'), true);
$res = [];

try {
    // repel http request method
    if ($_SERVER['REQUEST_METHOD'] != 'POST') {
        throw new \RequestMethodException('POST', $_SERVER['REQUEST_METHOD']);
    }

    // localize input
    $_POST = \model\Localizer::LocalizeArray($_POST);

    // valid input
    if (!valid\validate_signin_suica($_POST['sid'], $_POST['idm'])) {
        throw new valid\ValidationException('Sign in suica data is invalid');
    }

    session_start();

    // auth success
    if (auth\authenticate_suica($_POST['sid'], $_POST['idm'])) {
        $_SESSION['user'] = $_POST['sid'];
        $_SESSION['log_in'] = true;
        $logger->appendRecord("[{$_POST['sid']}] logged in successfully with suica.");
        $res['status'] = 1;
    }
    // auth fail
    else {
        $logger->appendRecord("[{$_POST['sid']}] attempted but fail to login with suica.");
    }
} catch (\RequestMethodException $re) {
    // inappropriate request method
    $logger->appendError($re);
    $res['error'] = [
        'message' => 'inappropriate request method.',
        'code' => 1
    ];
    $res['status'] = 0;
} catch (valid\ValidationException $ve) {
    // invalid input
    $logger->appendError($ve);
    $res['error'] = [
        'message' => 'invalid data supplied.',
        'code' => 2
    ];
    $res['status'] = 0;
} catch (auth\AuthenticationException $ae) {
    // no registration found
    $logger->appendError($ae);
    $res['error'] = [
        'message' => 'registration record not found.',
        'code' => 3
    ];
    $res['status'] = 0;
} catch (\Throwable $th) {
    $logger->appendError($th);
}

ob_start();
echo json_encode($res);
ob_end_flush();
