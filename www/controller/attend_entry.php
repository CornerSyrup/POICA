<?php

/**
 * entry point for attendance management handling
 * 
 * take data from various method:
 * 
 * take GET data: (from body, not query string)
 * 
 * use session data:
 * user:    student id.
 * log_in:  is logged in.
 * 
 * respond in json:
 * status:  positive for success, negative for error.
 *          -1 for http method, -2 for unauthorized
 * error:   error message.
 */

namespace controller;

// general
require_once 'model/Authentication.php';
require_once 'model/Logger.php';

use model\authentication as auth;

$logger = new \model\Logger('entry', 'attend');
/**
 * Respond data set.
 */
$res = [];

session_start();
session_regenerate_id();


try {
    if (!auth\authenticate()) {
        throw new auth\UnauthorizeException();
    }

    switch (strtoupper(strtoupper($_SERVER['REQUEST_METHOD']))) {
        default:
            throw new \RequestMethodException('', strtoupper($_SERVER['REQUEST_METHOD']));
            break;
    }

    $logger->SetTag('entry');
} catch (\RequestMethodException $rmx) {
    $logger->appendError($rmx);
    $res['status'] = -1;
    $res['error'] = 'Inappropriate request method';
} catch (auth\UnauthorizeException $uax) {
    $logger->appendError($uax);
    $res['status'] = -2;
    $res['error'] = 'Unauthorized request';
} catch (\Throwable $th) {
    $logger->appendError($th);
}

header("Content-Type: application/json");
echo json_encode($res);
