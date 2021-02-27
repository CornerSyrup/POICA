<?php

namespace POICA\model {
    /**
     * Data validation function service provider.
     */
    class Validator
    {
        #region authentication
        /**
         * Validate student ID.
         */
        static function validate_sid(string $id): bool
        {
            return preg_match('/^\d{5}$/', $id);
        }

        /**
         * Validate teacher ID.
         */
        static function validate_tid(string $id): bool
        {
            return preg_match('/^\d{6}$/', $id);
        }

        /**
         * Validate password.
         */
        static function validate_password(string $pwd): bool
        {
            return   preg_match('/^[A-Za-z0-9]+$/', $pwd);
        }

        /**
         * Validate suica IDm hash, which hashed with SHA256.
         */
        static function validate_suica(string $hash): bool
        {
            return preg_match('/^[A-Za-z0-9]{64}$/', $hash);
        }
        #endregion

        #region japanese
        /**
         * Validate if the given string is all Japanese kanji.
         */
        static function validate_kanji(string $value)
        {
            return preg_match('/^[\x{4E00}-\x{9FBF}]+$/u', $value);
        }

        /**
         * Validate if the given string is al Japanese hiragana.
         */
        static function validate_hiragana(string $value)
        {
            return preg_match('/^[\x{3040}-\x{309F}]+$/u', $value);
        }

        /**
         * Validate if the given string is al Japanese katakana.
         */
        static function validate_katakana(string $value)
        {
            return preg_match('/^[\x{30A0}-\x{30FF}]+$/u', $value);
        }
        #endregion
    }
}
