<?php

/**
 * Data and value validation service provider, including validation of GET/POST values.
 */

namespace model\validation;

function validate_sid(string $sid): bool
{
    return preg_match('/^\d{5}$/', $sid);
}

function validate_pwd(string $password): bool
{
    return preg_match('/^.+$/', $password);
}

function validate_suica(string $idm): bool
{
    return preg_match('/^\d{16}$/', $idm);
}

function validate_jname(string $name): bool
{
    // TODo: add validation rule
    return true;
}

function validate_jkana(string $kana): bool
{
    // TODO: add validation rule
    return true;
}

/**
 * Validate whether the sign in info for from sign in is valid.
 *
 * @param string $sid student id of the user.
 * @param string $password password of the user.
 * @return boolean
 * @throws ExpressionMismatchException throw when sid or password is invalid; variable name as `sid` and `pwd`.
 */
function validate_signin_form(string $sid, string $password): bool
{
    if (empty($sid) || empty($password)) {
        return false;
    }

    if (!validate_sid($sid)) {
        throw new ExpressionMismatchException('sid', $sid);
    }

    if (!validate_pwd($password)) {
        throw new ExpressionMismatchException('pwd', $password);
    }
    return true;
}

/**
 * Validate whether the sign in info for suica sign in is valid.
 *
 * @param string $sid student id of the user.
 * @param string $idm idm code of the suica card.
 * @return boolean
 */
function validate_signin_suica(string $sid, string $idm): bool
{
    if (empty($sid) || empty($idm)) {
        return false;
    }

    return validate_sid($sid) && validate_suica($idm);
}

function validate_signup_form(array $form): bool
{
    return validate_sid($form['sid']) &&
        validate_pwd($form['pwd']) &&
        validate_jname($form['jfn']) &&
        validate_jname($form['jln']) &&
        validate_jkana($form['jfk']) &&
        validate_jkana($form['jlk']);
}

/**
 * Exception representing some string do not match an expression rule.
 */
class ExpressionMismatchException extends \Exception
{
    public $var = '';
    /**
     * Constructor of expression mismatch exception.
     *
     * @param String $varName name of the variable which leads to this exception.
     * @param String $value value received.
     * @param String $expected value expected.
     * @param Integer $code exception code for exception instance.
     * @param Exception $innerException inner exception of instance.
     */
    public function __construct(string $varName, string $value, int $code = 0, \Exception $innerException = null)
    {
        $this->var = $varName;

        $message = "variable [${varName}] revived unexpected value of [${value}] mismatching its expected expression rule.";
        parent::__construct($message, $code, $innerException);
    }
}
