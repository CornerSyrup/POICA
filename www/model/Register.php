<?php

namespace model;

use UnexpectedValueException;

/**
 * Registration service provider, including user registration.
 */
class Register
{
    public static function reg_new_user(string $sid, string $password): bool
    {
        if (!Validator::validate_sid($sid)) {
            throw new UnexpectedValueException(`sid require numeric only string, but received [${sid}] as argument`);
        }

        if (!Validator::validate_pwd($password)) {
            throw new UnexpectedValueException(`password passed do not match password rule`);
        }

        DBAdaptor::insert_credential($sid, Authenticator::get_password_hash($password));
        return false;
    }
}
