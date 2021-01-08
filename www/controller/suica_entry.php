<?php

/**
 * suica registration process handler.
 * 
 * use session data:
 * user:    student id.
 * log_in:  is logged in.
 */

namespace controller;

require_once 'model/Authentication.php';
require_once 'model/Global.php';
require_once 'model/Logger.php';

use model\authentication as auth;

session_start();
session_regenerate_id();

$logger = new \model\Logger('entry', 'suica');
$res = [
    'status' => 0
];

try {
    if (!auth\authenticate()) {
        throw new auth\UnauthorizeException();
    }

    switch (strtoupper(strtoupper($_SERVER['REQUEST_METHOD']))) {
        case 'POST':
            require './suica/suica_post.php';
        case 'PUT':
            // TODO: create put handler for suica
        case 'DELETE':
            // TODO: create delete handler for suica
            break;
        default:
            throw new \RequestMethodException('', strtoupper($_SERVER['REQUEST_METHOD']));
    }

    $logger->SetTag('entry');
} catch (auth\UnauthorizeException $uax) {
    $logger->appendError($uex);
    $res = [
        'status' => 0,
        'error' => [
            'message' => 'Unauthorized request',
            'code' => 1
        ]
    ];
} catch (\RequestMethodException $re) {
    $logger->appendError($re);
    $res = [
        'status' => 0,
        'error' => [
            'message' => 'Inappropriate request method',
            'code' => 2
        ]
    ];
} catch (\Throwable $th) {
    $logger->appendError($th);
    $res = [
        'status' => 0,
        'error' => [
            'message' => '',
            'code' => 0
        ]
    ];
}

header("Content-Type: application/json");
echo json_encode($res);
