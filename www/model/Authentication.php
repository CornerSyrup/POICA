<?php

/**
 * Internal authentication service provider.
 */

namespace model\authentication;

require 'DBAdaptor.php';
require 'Logger.php';
require 'Validation.php';

use model;

/**
 * Authenticate user with password.
 *
 * @param string $sid student id of the user.
 * @param string $password password of the user as plain string.
 * @return boolean
 * @throws AuthenticationException throw when credential cannot fround in database.
 */
function authenticate_form(string $sid, string $password): bool
{
    try {
        $hash = (new model\DBAdaptor())->obtain_credential($sid);
    } catch (model\RecordNotFoundException $rnf) {
        throw new AuthenticationException("student id [{$sid}] was not registered", 0, $rnf);
    }

    return verify_password($password, $hash);
}

/**
 * Authenticate user with suica card.
 *
 * @param string $sid student id of the user.
 * @param string $idm idm code of the suica card.
 * @return boolean
 * @throws AuthenticationException throw when suica card data cannot found in database.
 */
function authenticate_suica(string $sid, string $idm): bool
{
    try {
        $uid = (new model\DBAdaptor())->obtain_suica($idm);
    } catch (model\RecordNotFoundException $rnf) {
        throw new AuthenticationException("suica [{$idm}] was not registered", 0, $rnf);
    }

    return $sid == $uid;
}

/**
 * Validate and store credential to database.
 *
 * @param array $data array of basic credential of user.
 * @return boolean true on success, false on fail.
 */
function enrol(array $data): bool
{
    $logger = new model\Logger('auth');

    $data['yr'] = substr(date('Y'), 0, 2);
    $data['pwd'] = get_password_hash($data['pwd']);

    try {
        (new model\DBAdaptor())->insert_credential($data);
        $logger->appendRecord("Success on enrolment of usership, with student id [{$data['sid']}]");
    } catch (model\RecordInsertException $rie) {
        $logger->appendError($rie);
        return false;
    }

    return true;
}

/**
 * Get password hash.
 *
 * @param string $password plain password string to be hashed.
 * @return string hash of password.
 */
function get_password_hash(string $password): string
{
    return password_hash($password, PASSWORD_DEFAULT);
}

/**
 * Verify whether password is correct.
 *
 * @param string $input password received.
 * @param string $pwd_hash password hash obtained from database.
 * @return boolean
 */
function verify_password(string $input, string $pwd_hash): bool
{
    return password_verify($input, $pwd_hash);
}

class AuthenticationException extends \Exception
{
    public function __construct(string $reason, int $code = 0, \Exception $innerException = null)
    {
        parent::__construct("Authentication process failed, for {$reason}.", $code, $innerException);
    }
}
