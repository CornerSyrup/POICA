<?php

namespace POICA\handler {
    /**
     * Interface provide capability of request handling.
     */
    interface IHandleable
    {
        /**
         * Get result of handling.
         */
        function get_result(): array;

        /**
         * Handle process of the request.
         */
        function handle(): array;

        /**
         * validate whether data supplied is valid for handling.
         */
        function validate(): bool;
    }
}
