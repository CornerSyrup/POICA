<?php

namespace POICA\model\exception {
    /**
     * Exception representing inappropriate http request method.
     */
    class RequestMethodException extends \Exception
    {
        /**
         * Initiate a request method exception.
         *
         * @param array $expected Array of expected request methods name.
         * @param string $actual Actual request method received.
         */
        public function __construct(array $expected, string $actual, int $code = 0, \Exception $innerException = null)
        {
            $actual = strtoupper($actual);
            foreach ($expected as $key => $value) {
                $expected[$key] = strtoupper($value);
            }

            $exp = implode(', ', $this->expected);

            parent::__construct(
                "Request method expected [{$exp}] instead of [{$actual}].",
                $code,
                $innerException
            );
        }
    }
}
