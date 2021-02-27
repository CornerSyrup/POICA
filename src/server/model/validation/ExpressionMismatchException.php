<?php

namespace POICA\model\validation {
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
}
