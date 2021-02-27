<?php

namespace POICA\model\exception {
    /**
     * Exception representing record cannot be found in database.
     */
    class RecordNotFoundException extends \Exception
    {
        /**
         * Constructor of record not found exception.
         */
        public function __construct(string $message, int $code = 0, \Exception $innerException = null)
        {
            parent::__construct($message, $code, $innerException);
        }
    }
}
