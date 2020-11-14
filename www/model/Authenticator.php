<?php

namespace model;

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
     * @throws ExpressionMismatchException throw when student id (sid) or password (pwd) found invalid.
     * @throws RecordNotFoundException throw then credential not fround from database.
     */
    public static function authenticate(string $sid, string $password): bool
    {
        if (!Validator::validate_sid($sid)) {
            throw new ExpressionMismatchException('sid', $sid);
        }

        if (!Validator::validate_pwd($password)) {
            throw new ExpressionMismatchException('pwd', $password);
        }

        try {
            $hash = DBAdaptor::obtain_credential($sid);
            $ret = Authenticator::verify_password($password, $hash);
        } catch (\Throwable $th) {
            throw $th;
        }

        return $ret;
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
