<?php

namespace POICA\authentication {
    /**
     * Exception representing unauthorized invocation.
     */
    class UnauthorizeException extends \Exception
    {
        public function __construct(int $code = 0, \Exception $innerException = null)
        {
            parent::__construct(
                "Attempted to invoke action with out authentication.",
                $code,
                $innerException
            );
        }
    }
}
