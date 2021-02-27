<?php

namespace POICA\authentication {
    /**
     * Exception representing authentication process failure.
     */
    class AuthenticationException extends \Exception
    {
        /**
         * Instantiate a authentication exception.
         *
         * @param string $reason Reason of process failure.
         */
        public function __construct(string $reason, int $code = 0, \Exception $innerException = null)
        {
            parent::__construct(
                "Authentication process failed, for {$reason}.",
                $code,
                $innerException
            );
        }
    }
}
