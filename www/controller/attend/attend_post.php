<?php

/**
 * attendance management POST method sub-handler
 * 
 * take POST data:
 * idm:     idm code collection for the student to be attend.
 * cls:     lesson id for that the student will be attend.
 * 
 * use session data:
 * user:    student id.
 * 
 * set $res from invoker:
 * status:  1 for success, 0 for failure.
 * error:   `message` for error message, `code` for error code.
 *          0 for unknown reason, 1 for invalid, 2 for insertion.
 */

namespace controller\attend;

require_once 'model/DBAdaptor.php';
require_once 'model/Localizer.php';
require_once 'model/Logger.php';
require_once 'model/Validation.php';

use model\validation as valid;

$logger->SetTag('post');

try {
    if (empty($_POST)  && strtolower($_SERVER["CONTENT_TYPE"]) == 'application/json') {
        $_POST = json_decode(file_get_contents('php://input'), true);
    }

    $_POST = \model\Localizer::LocalizeArray($_POST);

    // valid input
    // TODO: create new valid rule
    if (true) {
        throw new valid\ValidationException("Idm code [{$_POST['idm']}] is invalid");
    }

    $dba = new \model\DBAdaptor();
    // TODO: create new database access func
    $logger->appendRecord("Success to record students {$foo} attend lesson [{$_POST['cls']}].");
    $res['status'] = 1;
} catch (valid\ValidationException $ve) {
    $logger->appendError($ve);
    $res = [
        'status' => 0,
        'error' => [
            'message' => 'Invalid idm code.',
            'code' => 1
        ]
    ];
} catch (\model\RecordInsertException $rie) {
    $logger->appendError($rie);
    $res = [
        'status' => 0,
        'error' => [
            'message' => 'Invalid idm code.',
            'code' => 2
        ]
    ];
    http_response_code(500);
} catch (\Throwable $th) {
    $logger->appendError($th);
    $res = [
        'status' => 0,
        'error' => [
            'message' => 'Invalid idm code.',
            'code' => 0
        ]
    ];
}
