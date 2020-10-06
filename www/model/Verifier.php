<?php

namespace model;

class Verifier
{
    public static function verify_sid(string $sid): bool
    {
        return preg_match('/^\d+$/', $sid);
    }

    public static function verify_pwd(string $password): bool
    {
        return preg_match('/^*+$/', $password);
    }
}
