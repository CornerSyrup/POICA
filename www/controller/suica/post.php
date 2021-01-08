<?php

/**
 * suica POST method sub-handler.
 * 
 * task POST data:
 * idm:     idm code of suica card.
 * 
 * use session data:
 * user:    student id.
 * 
 * set $res from invoker:
 * status:  1 for success, 0 for failure.
 * error:   `message` for error message, `code` for error code.
 *          3 for invalid, 4 for insertion.
 */

namespace controller\suica;

require_once 'model/DBAdaptor.php';
require_once 'model/Localizer.php';
require_once 'model/Logger.php';
require_once 'model/Validation.php';

use model\validation as valid;

$logger->SetTag('post');

try {
    // accept js fetch api
    if (empty($_POST)  && strtolower($_SERVER["CONTENT_TYPE"]) == 'application/json') {
        $_POST = json_decode(file_get_contents('php://input'), true);
    }

    $_POST = \model\Localizer::LocalizeArray($_POST);

    // valid input
    if (!valid\validate_suica($_POST['idm'])) {
        throw new valid\ValidationException("Idm code [{$_POST['idm']}] is invalid");
    }

    // update attribute of suica
    // TODO: check if not registered before, return registered if so. 
    (new \model\DBAdaptor())->update_suica($_SESSION['user'], $_POST['idm']);
    $logger->appendRecord("Success to register suica card with idm [{$_POST['idm']}] to user with student id [{$_SESSION['user']}]");
    $res['status'] = 1;
} catch (valid\ValidationException $ve) {
    $logger->appendError($ve);
    $res = [
        'message' => 'Invalid idm code.',
        'code' => 3
    ];
} catch (\model\RecordInsertException $rie) {
    $logger->appendError($rie);
    $res = [
        'message' => '',
        'code' => 4
    ];
    http_response_code(500);
}
