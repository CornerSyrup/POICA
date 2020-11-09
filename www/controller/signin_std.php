<?php

/**
 * standard sign in process with student id and password
 */

namespace controller;

use model\Authenticator;
use model\Localizer;
use model\Validator;

include dirname(__DIR__) . '/model/Localizer';
include dirname(__DIR__) . '/model/DBAdapter';

session_start();
session_destroy();

try {
    Localizer::LocalizeArray($_POST);

    if (Validator::validate_sid($_POST['sid'])) {
        if (Validator::validate_pwd($_POST['pwd'])) {
            if (Authenticator::authenticate($_POST['sid'], $_POST['pwd'])) {
                // auth succeed
            } else {
                // auth failed
            }
        } else {
            // password error
        }
    } else {
        // sid error
    }
} catch (\Throwable $th) {
    //throw $th;
} finally {
    session_start();
}
