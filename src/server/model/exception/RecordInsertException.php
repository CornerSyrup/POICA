<?php

namespace POICA\model\exception {
    /**
     * Exception representing record insertion failure.
     */
    class RecordInsertException extends \Exception
    {
        /**
         * Constructor of record insert exception.
         */
        public function __construct(string $message, int $code = 0, \Exception $innerException = null)
        {
            parent::__construct($message, $code, $innerException);
        }
    }
}
