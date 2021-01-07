<?php

/**
 * Data and value validation service provider, including validation of GET/POST values.
 */

namespace model\validation;

#region authentication
function validate_sid(string $sid): bool
{
    return preg_match('/^\d{5}$/', $sid);
}

/**
 * Validate teacher id.
 *
 * @param string $tid
 * @return boolean
 */
function validate_tid(string $tid): bool
{
    if (empty($tid)) {
        return false;
    }

    return preg_match('/^\d{6}$/', $tid);
}

function validate_pwd(string $password): bool
{
    return preg_match('/^.+$/', $password);
}

function validate_suica(string $idm): bool
{
    return preg_match('/^[A-Za-z0-9]{16}$/', $idm);
}
#endregion

function validate_jname(string $name): bool
{
    if (empty($name)) {
        return false;
    }

    // TODo: add validation rule
    return true;
}

function validate_jkana(string $kana): bool
{
    if (empty($kana)) {
        return false;
    }

    // TODO: add validation rule
    return true;
}

/**
 * Validate whether supplied data is a date.
 *
 * @param integer $date The date to be validate.
 * @return boolean
 */
function validate_date(int $date): bool
{
    // ! todo: implement validation rule
    return is_numeric($date);
}

/**
 * Validate whether supplied date is in range.
 *
 * @param integer $date Date to be validate, in unix timestamp.
 * @param integer $before Latest date, in unix timestamp.
 * @param integer $after Earliest date, in unix timestamp.
 * @return boolean
 */
function validate_date_range(int $date, int $before, int $after): bool
{
    return $date > $after && $date < $before;
}

#region applications
/**
 * Check whether class code is valid.
 *
 * @param string $code Format ih12a092, non case sensitive, class room number required.
 * @return boolean
 */
function validate_class_code(string $code): bool
{
    if (empty($code)) {
        return false;
    }

    return preg_match('/^\w{2}\d{2}\w\d{3}$/', $code);
}

function validate_resident_card(string $code): bool
{
    if (empty($code)) {
        return false;
    }

    return preg_match('/^\w{2}\d{8}\w{2}$/', $code);
}
#endregion

#region array validation
/**
 * Validate whether the sign in info for from sign in is valid.
 *
 * @param string $sid student id of the user.
 * @param string $password password of the user.
 * @return boolean
 */
function validate_signin_form(string $sid, string $password): bool
{
    if (empty($sid) || empty($password)) {
        return false;
    }

    return validate_sid($sid) && validate_pwd($password);
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
    if (
        empty($form['sid']) || empty($form['pwd']) ||
        empty($form['jfn']) || empty($form['jln']) ||
        empty($form['jfk']) || empty($form['jlk'])
    ) {
        return false;
    }

    return validate_sid($form['sid']) &&
        validate_pwd($form['pwd']) &&
        validate_jname($form['jfn']) &&
        validate_jname($form['jln']) &&
        validate_jkana($form['jfk']) &&
        validate_jkana($form['jlk']);
}
#endregion

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

        parent::__construct("Variable [${varName}] revived unexpected value of [${value}] mismatching its expected expression rule.", $code, $innerException);
    }
}

/**
 * Exception representing validation failure.
 */
class ValidationException extends \Exception
{
    public function __construct(string $message = '', int $code = 0, \Exception $innerException = null)
    {
        parent::__construct($message, $code, $innerException);
    }
}
