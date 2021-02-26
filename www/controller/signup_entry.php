<?php

/**
 * standard sign up process with student id and password
 */

namespace controller;

require_once 'model/Authentication.php';
require_once 'model/Global.php';
require_once 'model/Logger.php';

use model\authentication as auth;

// clear previous login status
session_start();
session_regenerate_id();

/**
 * Logger to keep data record.
 */
$logger = new \model\Logger('entry', 'signup');
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

try {
    switch (strtoupper($_SERVER['REQUEST_METHOD'])) {
        case 'GET':
            ob_start();
            include "view/sign.html";
            ob_end_flush();
            exit;
        case 'POST':
            require_once './signup/post.php';
            $handler = new signup\PostHandler($logger);
            break;
        default:
            throw new \RequestMethodException('', strtoupper($_SERVER['REQUEST_METHOD']));

            break;
    }

    if (empty($handler)) {
        throw new \Exception('Null handler');
    } else if ($handler->Validate()) {
        $handler->Handle();
    }

    $res = $handler->GetResult();
} catch (\RequestMethodException $re) {
    // inappropriate request method
    $logger->appendError($re);
    $res['status'] = 11;
} catch (\Throwable $th) {
    $logger->appendError($th);
    $res['status'] = 11;
}

header("Content-Type: application/json");
echo json_encode($res);
