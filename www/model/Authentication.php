<?php

/**
 * Internal authentication service provider.
 */

namespace model\authentication;

require 'DBAdaptor.php';
require 'Logger.php';
require 'Validator.php';

use model;
use model\validation as valid;

/**
 * Authenticate user whether he is legal.
 *
 * @param string $sid student id.
 * @param string $password password string.
 * @return boolean
 * @throws ExpressionMismatchException throw when student id (sid) or password (pwd) found invalid.
 * @throws RecordNotFoundException throw then credential not fround from database.
 */
function authenticate(string $sid, string $password): bool
{
    if (!valid\validate_sid($sid)) {
        throw new valid\ExpressionMismatchException('sid', $sid);
    }

    if (!valid\validate_pwd($password)) {
        throw new valid\ExpressionMismatchException('pwd', $password);
    }

    try {
        $hash = (new model\DBAdaptor())->obtain_credential($sid);
    } catch (\Throwable $th) {
        throw $th;
    }

    return verify_password($password, $hash);
}

function authenticate_suica(string $sid, string $idm): bool
{
    if (!valid\validate_sid($sid)) {
        throw new valid\ExpressionMismatchException('sid', $sid);
    }
    if (!valid\validate_suica($idm)) {
        throw new valid\ExpressionMismatchException('idm', $idm);
    }

    try {
        $uid = (new model\DBAdaptor())->obtain_suica($idm);
    } catch (model\RecordNotFoundException $rnf) {
        throw new \Exception("Suica [{$idm}] was not registered.", 0, $rnf);
    } catch (\Throwable $th) {
        throw new $th;
    }

    return $sid == $uid;
}

function enrol(array $data): bool
{
    $logger = new model\Logger('auth');

    $data['yr'] = substr(date('Y'), 0, 2);
    $data['pwd'] = get_password_hash($data['pwd']);

    try {
        (new model\DBAdaptor())->insert_credential($data);
        $logger->appendRecord("Success on enrolment of usership, with student id [{$data['sid']}]");
    } catch (\Throwable $th) {
        $logger->appendError($th);
        return false;
    }

    return true;
}

/**
 * Get password hash.
 *
 * @param string $password password string to be hashed.
 * @return string hash of password.
 */
function get_password_hash(string $password): string
{
    return password_hash($password, PASSWORD_DEFAULT);
}

/**
 * Verify whether password input is valid.
 *
 * @param string $input password received.
 * @param string $pwd_hash password hash obtained from db.
 * @return boolean
 */
function verify_password(string $input, string $pwd_hash): bool
{
    return password_verify($input, $pwd_hash);
}
