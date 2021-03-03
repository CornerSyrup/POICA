<?php

/**
 * Entry point of authentication related process.
 */

require __DIR__ . '/../../vendor/autoload.php';

use POICA\model as model;
use POICA\model\exception as ex;
use POICA\authentication as auth;
use POICA\controller\auth as handler;

session_start();

#region variables
/**
 * Logger to keep record.
 */
$logger = new model\Logger('entry', 'auth');
/**
 * Response data to request.
 * 
 * @var array
 */
$response = ['status' => 0];
/**
 * Handler model.
 * 
 * @var IHandleable
 */
$handler = null;
#endregion

try {
    // sign out from get method
    if (strtoupper($_SERVER['REQUEST_METHOD']) == 'GET')
        include __DIR__ . '/index.php';
    // else then post will be treat as illegal
    elseif (strtoupper($_SERVER['REQUEST_METHOD']) != 'POST')
        throw new ex\RequestMethodException(
            ['post'],
            $_SERVER['REQUEST_METHOD']
        );

    switch ($_REQUEST['m']) {
        case 'in':
            // form sign in
            if ($_REQUEST['d'] == 'f')
                $handler = new handler\FormSignInHandler($logger);
            // suica sign in
            elseif ($_REQUEST['d'] == 's')
                $handler = new handler\SuicaSignInHandler($logger);
            break;
        case 'up':
            $handler = new handler\SignUpHandler($logger);
            break;
    }

    if (is_null($handler))
        throw new \Exception('null handler');
    elseif ($handler->validate())
        $handler->handle();

    $response = $handler->get_result();
}
#region exception
catch (ex\RequestMethodException $rme) {
    $logger->append_error($rme);
    $response = ['status' => 11];
} catch (\JsonException $jsx) {
    $logger->append_error($rme);
    $response = ['status' => 12];
} catch (auth\AuthenticationException $aue) {
    $logger->append_error($aue);
    $response = ['status' => 21];
} catch (\Throwable $th) {
    $logger->append_error($th);
    $response = ['status' => 10];
}
#endregion

session_regenerate_id();
header('Content-Type: application/json');
echo model\Helper::json_stringify($response);
