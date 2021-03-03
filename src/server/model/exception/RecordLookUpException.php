<?php

namespace POICA\model\exception {
    /**
     * Exception representing error encountered in record look up procedure.
     */
    class RecordLookUpException extends \Exception
    {

        /**
         * Constructor of record look up exception.
         */
        public function __construct(string $message, int $code = 0, \Exception $innerException = null)
        {
            parent::__construct("Fail to lookup record with following message:\n\t" . $message, $code, $innerException);
        }
    }
}
