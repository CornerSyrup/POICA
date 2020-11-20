<?php

namespace model\authentication;

require 'DBAdaptor.php';
require 'Logger.php';
require 'Validator.php';

use model\validation as valid;

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
        if (!valid\validate_sid($sid)) {
            throw new valid\ExpressionMismatchException('sid', $sid);
        }

        if (!valid\validate_pwd($password)) {
            throw new valid\ExpressionMismatchException('pwd', $password);
        }

        try {
            $hash = DBAdaptor::obtain_credential($sid);
            $ret = Authenticator::verify_password($password, $hash);
        } catch (\Throwable $th) {
            throw $th;
        }

        return $ret;
    }

    public static function enrolment(array $data): bool
    {
        $logger = new Logger('auth');

        $data['yr'] = substr(date('Y'), 0, 2);
        $data['pwd'] = self::get_password_hash($data['pwd']);

        try {
            DBAdaptor::insert_credential($data);
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
