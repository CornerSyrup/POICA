<?php

namespace POICA\model {
    /**
     * Helper class provide static functions.
     */
    class Helper
    {
        /**
         * Stringify value to JSON.
         */
        public static function json_stringify($value): string
        {
            return json_encode($value, JSON_THROW_ON_ERROR);
        }

        /**
         * Parse JSON into associated array.
         */
        public static function json_parse($json): array
        {
            return json_decode($json, true, 512, JSON_THROW_ON_ERROR);
        }
    }
}
