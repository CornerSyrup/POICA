<?php

namespace model;

class Validator
{
    public static function validate_sid(string $sid): bool
    {
        return preg_match('/^\d+$/', $sid);
    }

    public static function validate_pwd(string $password): bool
    {
        return preg_match('/^*+$/', $password);
    }
}
