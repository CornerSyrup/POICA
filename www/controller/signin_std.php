<?php

/**
 * standard sign in process with student id and password
 */

namespace controller;

require_once 'model/Authenticator.php';
require_once 'model/Logger.php';
require_once 'model/Localizer.php';

use model\authentication as auth;
use model\validation as valid;

session_start();
session_destroy();

$logger = new \model\Logger('auth');
$view = 'signin_form';
$errmsg = '';

try {
    if (empty($_POST['sid']) || empty($_POST['pwd'])) {
        throw new \Exception("Insufficient data for sign in received.");
    } else {
        $_POST = \model\Localizer::LocalizeArray($_POST);
    }

    if (auth\authenticate($_POST['sid'], $_POST['pwd'])) {
        // auth success
        $_SESSION['user'] = $_POST['sid'];
        $_SESSION['log_in'] = true;
        $logger->appendRecord("[{$_POST['sid']}] logged in successfully.");
        $view = 'signin_success';
    } else {
        // auth failed
        $logger->appendRecord("[{$_POST['sid']}] attempted to login, but failed.");
    }
} catch (valid\ExpressionMismatchException $eme) {
    if ($eme->var == 'sid') {
        $logger->appendError(new \Exception("[{$_POST['sid']}] attempted to log in, but invalid student id supplied.", 0, $eme));
        $errmsg = "Please check student id and try again.";
    } elseif ($eme->var == 'pwd') {
        $logger->appendError(new \Exception("[{$_POST['sid']}] attempted to log in, but invalid password supplied.", 0, $eme));
        $errmsg = "Please check password and try again.";
    }
} catch (\model\RecordNotFoundException $rnf) {
    $logger->appendError(new \Exception("[{$_POST['sid']}] attempted to log in, but invalid sid provided.", 0, $rnf));
    $errmsg = "Account not found, please <a href=\"/signup/\">create a new account</a>.";
} catch (\Throwable $th) {
    $logger->appendError($th);
} finally {
    session_start();
    include "view/{$view}.php";
}
