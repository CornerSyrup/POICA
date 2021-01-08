<?php

/**
 * entry point of application form data handling.
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
$logger = new \model\Logger('entry', 'apply');
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
            if ($_REQUEST['id'] == 0) {
                require_once './apply/get_catalogue.php';
                $handler = new apply\GetCatalogueHandler($logger);
            } else {
                require_once './apply/get.php';
                $handler = new apply\GetHandler($logger, $_REQUEST);
            }
            break;
        case 'POST':
            require_once './apply/post.php';
            $handler = new apply\PostHandler($logger);
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
        $res['status'] = 14;
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
    $res['status'] = 0;
}

header("Content-Type: application/json");
echo json_encode($res);
