<?php

namespace model;

use model\DBAdaptor;
use model\ExpressionMismatchException;
use model\Logger;
use model\Validator;

require 'DBAdaptor.php';
require 'Logger.php';
require 'Validator.php';

/**
 * Internal authentication service provider.
 */
class Authenticator
{
    /**
     * Authenticate user whether he is legal.
     *
     * @param string $sid student id.
     * @param string $password password string.
     * @return boolean
     */
    public static function authenticate(string $sid, string $password): bool
    {
        if (!Validator::validate_sid($sid)) {
            throw new ExpressionMismatchException('sid', $sid);
        }

        if (!Validator::validate_pwd($password)) {
            throw new ExpressionMismatchException('password', $password);
        }

        $log  = new Logger('auth');

        try {
            $ret = Authenticator::verify_password($password, DBAdaptor::obtain_credential($sid));

            // $log->appendRecord("Authenticate succeed with student id ${sid}");
            echo $log->appendRecord("Authenticate succeed with student id ${sid}") ? 'log sus' : 'log fail';
        } catch (\Throwable $th) {
            $log->appendError($th);

            $ret = false;
        } finally {
            return $ret;
        }
    }

    /**
     * Get password hash.
     *
     * @param string $password password string to be hashed.
     * @return string hash of password.
     */
    public static function get_password_hash(string $password): string
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
    public static function verify_password(string $input, string $pwd_hash): bool
    {
        return password_verify($input, $pwd_hash);
    }
}
