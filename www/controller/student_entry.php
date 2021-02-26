<?php

/**
 * entry point of student data handling.
 */

namespace controller;

require_once 'model/Authentication.php';
require_once 'model/Global.php';
require_once 'model/Logger.php';

use model\authentication as auth;

session_start();
session_regenerate_id();

/**
 * Logger to keep data record.
 */
$logger = new \model\Logger('entry', 'student');
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
    if (!auth\authenticate()) {
        throw new auth\UnauthorizeException();
    }

    switch (strtoupper($_SERVER['REQUEST_METHOD'])) {
        case 'GET':
            require_once './student/get.php';
            $handler = new student\GetListHandler($logger, ['c' => $_GET['c']]);
            break;
        case 'POST':
            require_once './student/post.php';
            $handler = new student\PostHandler($logger, ['c' => $_GET['c']]);
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
} catch (auth\UnauthorizeException $uax) {
    $logger->appendError($uax);
    $res['status'] = 11;
} catch (\RequestMethodException $rmx) {
    $logger->appendError($rmx);
    $res['status'] = 12;
} catch (\JsonException $je) {
    $logger->appendError($je);
    $res['status'] = 13;
} catch (\Throwable $th) {
    $logger->appendError($th);
    $res['status'] = 10;
}

header("Content-Type: application/json");
echo json_stringify($res);