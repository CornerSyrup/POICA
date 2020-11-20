<?php

namespace controller;

require_once 'model/DBAdaptor.php';
require_once 'model/Localizer.php';
require_once 'model/Logger.php';
require_once 'model/Validator.php';

use model\authentication as auth;
use model\validation as valid;

session_start();

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    http_response_code(403); // return bad req
    die;
}

$logger = new \model\Logger('auth');
$_POST = json_decode(file_get_contents('php://input'), true);

try {
    if (empty($_POST['idm'])) {
        throw new \Exception("No suica idm code received.");
    } else {
        $_POST = \model\Localizer::LocalizeArray($_POST);

        if (!valid\validate_suica($_POST['idm'])) {
            throw new valid\ExpressionMismatchException('idm', $_POST['idm']);
        }
    }

    $_SESSION['user'] = \model\DBAdaptor::obtain_suica($_POST['idm']);
    $_SESSION['log_in'] = true;
    $logger->appendRecord("[{$_POST['idm']}] logged in with suica successfully.");

    http_response_code(200);
} catch (valid\ExpressionMismatchException $eme) {
    $logger->appendError(error_template($eme));
} catch (\model\RecordNotFoundException $rnf) {
    $logger->appendError(error_template($rnf));
} catch (\Throwable $th) {
    $logger->appendError($th);
}

function error_template(\Throwable $th): \Throwable
{
    return new \Exception("User attempted to log in with suica card, but invalid idm code supplied.", 0, $th);
}
