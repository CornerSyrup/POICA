<?php

/**
 * applied form catalogue GET method sub-handler.
 * 
 * use session data:
 * user:    student id.
 * 
 * set $res from invoker:
 * cat:     array of applied form.
 */

namespace controller\apply;

require_once 'model/DBAdaptor.php';
require_once 'model/Logger.php';

use model;

$logger->SetTag('get');

$dba = new model\DBAdaptor();
$res['cat'] = $dba->obtain_catalogue($_SESSION['user']);
$logger->appendRecord("[{$_SESSION['user']}] obtained applied form list.");
$res['status'] = 1;
