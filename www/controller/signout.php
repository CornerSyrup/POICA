<?php

require_once 'model/Authentication.php';

use  model\authentication as auth;;

@session_start();
auth\sign_out();

header("Location: http://{$_SERVER['HTTP_HOST']}/");
