<?php

namespace POICA\model\validation {
    /**
     * Exception representing validation failure.
     */
    class ValidationException extends \Exception
    {
        /**
         * Constructor of validation exception
         *
         * @param string $message Exception message.
         * @param integer $code Exception code.
         * @param \Exception $innerException Inner exception of instance.
         */
        public function __construct(string $message = '', int $code = 0, \Exception $innerException = null)
        {
            parent::__construct($message, $code, $innerException);
        }
    }
}
