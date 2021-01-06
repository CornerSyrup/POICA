<?php

/**
 * entry point application form data handling.
 * 
 * take GET data from QSA:
 * id:      application form data entry id.
 * 
 * use session data:
 * user:    student id.
 * log_in:  is logged in.
 */

namespace controller;

require_once 'model/Authentication.php';
require_once 'model/Global.php';
require_once 'model/Logger.php';

use Exception;
use model\authentication as auth;
use model\RecordNotFoundException;

/**
 * Logger to keep data record.
 */
$logger = new \model\Logger('entry', 'apply');
/**
 * Respond to request.
 */
$res = [];

session_start();

try {
    // if (!auth\authenticate()) {
    //     throw new auth\UnauthorizeException();
    // }

    $handler = null;

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
        throw new Exception('Null handler');
    } else if ($handler->Validate()) {
        $res = $handler->Handle();
    }

    $logger->SetTag('entry');
} catch (auth\UnauthorizeException $uax) {
    $logger->appendError($uax);
    $res['status'] = 11;
} catch (\RequestMethodException $rmx) {
    $logger->appendError($rmx);
    $res['status'] = 12;
} catch (\JsonException $je) {
    $logger->appendError($je);
    $res['status'] = 13;
} catch (RecordNotFoundException $rnf) {
    $logger->appendError($rnf);
    $res['status'] = 21;
} catch (\Throwable $th) {
    $logger->appendError($th);
    $res['status'] = 0;
}

header("Content-Type: application/json");
echo json_encode($res);
