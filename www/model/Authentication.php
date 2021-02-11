<?php

/**
 * Internal authentication service provider.
 */

namespace model\authentication;

require_once 'DBAdaptor.php';
require_once 'Logger.php';
require_once 'Validation.php';

use model;

/**
 * Authenticate whether user logged in wih session data.
 *
 * @return boolean
 */
function authenticate(): bool
{
    return $_SESSION['log_in'] ?? false;
}

/**
 * Sign out from this server. Basically unset credential.
 *
 * @return void
 */
function sign_out(): void
{
    unset($_SESSION['user']);
    unset($_SESSION['log_in']);
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

/**
 * Exception representing authentication process failure.
 */
class AuthenticationException extends \Exception
{
    public function __construct(string $reason, int $code = 0, \Exception $innerException = null)
    {
        parent::__construct("Authentication process failed, for {$reason}.", $code, $innerException);
    }
}

/**
 * Exception representing unauthorized invocation.
 */
class UnauthorizeException extends \Exception
{
    public function __construct(int $code = 0, \Exception $innerException = null)
    {
        parent::__construct("Attempted to invoke action with out authentication.", $code, $innerException);
    }
}
