<?php

/**
 * Entry point of lesson data manipulation.
 */

require_once __DIR__ . '/../../vendor/autoload.php';

use POICA\authentication as auth;
use POICA\model as model;
use POICA\model\exception as ex;
use POICA\controller\lesson as handler;

session_start();

#region variables
/**
 * Logger to keep record.
 */
$logger = new model\Logger('entry', 'lesson');
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
    if (!auth\Authenticator::authenticate_teacher())
        throw new auth\UnauthorizeException();

    switch (strtoupper($_SERVER['REQUEST_METHOD'])) {
        case 'GET':
            $handler = new handler\GetHandler($logger);
            break;
        default:
            throw new ex\RequestMethodException(
                ['get'],
                $_SERVER['REQUEST_METHOD']
            );
    }

    if (is_null($handler))
        throw new \Exception('null handler');
    elseif ($handler->validate())
        $handler->handle();

    $response = $handler->get_result();
}
#region exception
catch (auth\UnauthorizeException $uax) {
    $logger->append_error($uax);
    $response = ['status' => 11];
} catch (ex\RequestMethodException $rmx) {
    $logger->append_error($rmx);
    $response = ['status' => 12];
} catch (\JsonException $jsx) {
    $logger->append_error($jsx);
    $response = ['status' => 13];
} catch (\Throwable $th) {
    $logger->append_error($th);
    $response = ['status' => 10];
}
#endregion

session_regenerate_id();
header('Content-Type: application/json');
echo model\Helper::json_stringify($response);
