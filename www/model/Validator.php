<?php

namespace model;

use Exception;

/**
 * Data and value validation service provider, including validation of GET/POST values
 */
class Validator
{
    public static function validate_sid(string $sid): bool
    {
        return preg_match('/^\d{5}$/', $sid);
    }

    public static function validate_pwd(string $password): bool
    {
        return preg_match('/^*+$/', $password);
    }
}

/**
 * Exception representing some string do not match an expression rule
 */
class ExpressionMismatchException extends Exception
{
    /**
     * Constructor of expression mismatch exception
     *
     * @param String $varName name of the variable which leads to this exception
     * @param String $value value received
     * @param String $expected value expected
     * @param Integer $code exception code for exception instance
     * @param Exception $innerException inner exception of instance
     */
    public function __construct(string $varName, string $value, int $code = 0, Exception $innerException = null)
    {
        $message = "variable [${varName}] revived unexpected value of [${value}] mismatching its expected expression rule";
        parent::__construct($message, $code, $innerException);
    }
}
