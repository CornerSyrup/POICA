<?php

/**
 * entry point of sign in handling.
 */

namespace controller;

require_once 'model/Authentication.php';
require_once 'model/Global.php';
require_once 'model/Logger.php';

use model\authentication as auth;

session_start();
session_regenerate_id(true);

/**
 * Logger to keep data record.
 */
$logger = new \model\Logger('entry', 'signin');
/**
 * Respond to request.
 */
$res = [
    'status' => 0
];
/**
 * Handler model.
 */
$handler = null;

auth\sign_out();

try {
    switch (strtoupper($_SERVER['REQUEST_METHOD'])) {
        case 'GET':
            ob_start();
            include "view/signin.php";
            ob_end_flush();
            exit;
        case 'POST':
            require_once './signin/form.php';
            $handler = new signin\PostFormHandler($logger);
            break;
        default:
            throw new \RequestMethodException('', strtoupper($_SERVER['REQUEST_METHOD']));
            break;
    }

    if (empty($handler)) {
        throw new \Exception('Null handler');
    } else if ($handler->Validate()) {
        $handler->Handle();
    } else {
        $res['status'] = 13;
    }

    $res = $handler->GetResult();
} catch (\RequestMethodException $re) {
    // inappropriate request method
    $logger->appendError($re);
    $res['status'] = 11;
} catch (\Throwable $th) {
    $logger->appendError($th);
    $res['status'] = 10;
}

header("Content-Type: application/json");
echo json_encode($res);
