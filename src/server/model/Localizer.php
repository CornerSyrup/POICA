<?php

namespace POICA\model {
    class Localizer
    {
        /**
         * Localize array of data for system.
         */
        public static function localize_array(array $data): array
        {
            foreach ($data as $key => $value) {
                if (is_array($value)) {
                    $data[$key] = self::localize_array($value);
                } elseif (is_string($value)) {
                    $data[$key] = self::localize_string($value);
                }
            }

            return $data;
        }

        /**
         * Localize supplied string for system.
         */
        public static function localize_string(string $value)
        {
            return self::disarm(self::encode($value));
        }

        /**
         * Disarm supplied string.
         */
        private static function disarm(string $value): string
        {
            return htmlentities($value);
        }

        /**
         * Encode supplied value to utf-8.
         */
        private static function encode(string $value): string
        {
            return mb_convert_encoding($value, 'UTF-8', ['UTF-8', 'SJIS']);
        }
    }
}
