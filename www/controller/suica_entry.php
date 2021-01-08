<?php

/**
 * entry point of suica related handler.
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
$logger = new \model\Logger('entry', 'suica');
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
        case 'POST':
            require './suica/post.php';
            $handler = new suica\PostHandler($logger);
        case 'PUT':
            // TODO: create put handler for suica
        case 'DELETE':
            // TODO: create delete handler for suica
            break;
        default:
            throw new \RequestMethodException('', strtoupper($_SERVER['REQUEST_METHOD']));
            break;
    }

    if (empty($handler)) {
        throw new \Exception('Null handler');
    }
    else if ($handler->Validate()) {
        $handler->Handle();
    } else {
        $res['status'] = 14;
    }

    $res = $handler->GetResult();
} catch (auth\UnauthorizeException $uax) {
    $logger->appendError($uex);
    $res['status'] = 11;
} catch (\RequestMethodException $re) {
    $logger->appendError($re);
    $res['status'] = 12;
} catch (\JsonException $je) {
    $logger->appendError($je);
    $res['status'] = 13;
} catch (\Throwable $th) {
    $logger->appendError($th);
    $res['status'] = 0;
}

header("Content-Type: application/json");
echo json_encode($res);
