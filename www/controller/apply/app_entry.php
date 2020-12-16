<?php

/**
 * entry point application form data handling.
 * 
 * take data from various method:
 * typ:     type of form. doc as 証明書等発行願.
 * 
 * take POST data:
 * frm:     application form data.
 * 
 * take GET data from QSA:
 * id:      application form data entry id.
 * 
 * use session data:
 * user:    user id.
 * log_in:  is logged in.
 * 
 * respond in json:
 * status:  positive for success, negative for error.
 *          -1 for http method, -2 for auth, -3 for obtain, -4 for insert;
 *          1 for obtain, 2 for insert, 3 for update, 4 for delete.
 * error:   error message.
 */

namespace controller;

// general
require_once 'model/Authentication.php';
require_once 'model/Logger.php';

// form data handling model
require_once 'model/apply/AppForm.php';
require_once 'model/apply/DocIssue.php';

use model\authentication as auth;

$logger = new \model\Logger('entry', 'app');
/**
 * Respond data set.
 */
$res = [];

session_start();

try {
    if (!auth\authenticate()) {
        throw new auth\UnauthorizeException();
    }

    switch (strtoupper(strtoupper($_SERVER['REQUEST_METHOD']))) {
        case 'GET':
            // id 0 for catalogue request, see also .htaccess
            if ($_REQUEST['id'] == 0) {
                require_once 'app_get_catalogue.php';
            } else {
                require_once 'app_get.php';
            }
            break;
        case 'POST':
            // TODO: create post handler for application
            require_once 'app_post.php';
            break;
        case 'PUT':
            // TODO: create put handler for application
            require_once 'app_put.php';
            break;
        case 'DELETE':
            // TODO: create delete handler for application
            require_once 'app_delete.php';
            break;
        default:
            throw new \RequestMethodException('', strtoupper($_SERVER['REQUEST_METHOD']));
            break;
    }

    $logger->SetTag('entry');
} catch (\RequestMethodException $ex) {
    $logger->appendError($ex);
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
