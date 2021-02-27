<?php

namespace POICA\apply {
    /**
     * Exception representing form incompleteness.
     */
    class FormIncompleteException extends \Exception
    {
        /**
         * Constructor of form incomplete exception.
         *
         * @param string $field Fields that are not filled.
         * @param string $reason Reason of the suggested field required.
         */
        public function __construct(array $fields, string $reason = 'required', int $code = 0, \Exception $innerException = null)
        {
            $f = implode(', ', $fields);

            parent::__construct(
                "Field [{$f}] required for [{$reason}] but remain empty.",
                $code,
                $innerException
            );
        }
    }
}
