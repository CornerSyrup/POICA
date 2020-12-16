<?php

/**
 * form data GET method sub-handler.
 * 
 * take GET data:
 * id:      application form data entry id.
 * 
 * use session data:
 * user:    student id.
 * 
 * set $res from invoker:
 * frm:     form data.
 */

namespace controller\apply;

require_once 'model/DBAdaptor.php';
require_once 'model/Logger.php';

use model;

$logger->SetTag('get');

try {
    $dba = new model\DBAdaptor();
    $res['frm'] = $dba->obtain_form($_REQUEST['id'], $_SESSION['user']);
    $logger->appendRecord("[{$_SESSION['user']}] obtained application form entry [{$_REQUEST['id']}].");
    $res['status'] = 1;
} catch (model\RecordNotFoundException $rnf) {
    $logger->appendError($rnf);
    $res['status'] = -3;
    $res['error'] = 'Entry not found in database';
}
