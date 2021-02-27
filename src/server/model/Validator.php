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
        static function is_all_kanji(string $value): bool
        {
            return preg_match('/^[\x{4E00}-\x{9FBF}]+$/u', $value);
        }

        /**
         * Validate if the given string include Japanese kanji.
         */
        static function is_include_kanji(string $value): bool
        {
            return preg_match('/[\x{4E00}-\x{9FBF}]/u', $value) > 0;
        }

        /**
         * Validate if the given string is al Japanese hiragana.
         */
        static function is_all_hiragana(string $value): bool
        {
            return preg_match('/^[\x{3040}-\x{309F}]+$/u', $value);
        }

        /**
         * Validate if the given string include Japanese hiragana.
         */
        static function is_include_hiragana(string $value): bool
        {
            return preg_match('/[\x{3040}-\x{309F}]/u', $value) > 0;
        }

        /**
         * Validate if the given string is al Japanese katakana.
         */
        static function is_all_katakana(string $value): bool
        {
            return preg_match('/^[\x{30A0}-\x{30FF}]+$/u', $value);
        }

        /**
         * Validate if the given string include Japanese katakanas.
         */
        static function is_include_katakana(string $value): bool
        {
            return preg_match('/[\x{30A0}-\x{30FF}]/u', $value) > 0;
        }

        /**
         * Validate if the given string include Japanese characters.
         */
        static function is_include_japanese(string $value): bool
        {
            return self::is_include_hiragana($value)
                || self::is_include_katakana($value)
                || self::is_include_kanji($value);
        }
        #endregion

        #region form applications
        /**
         * Validate if the given string is valid class code, in form of ih12a092, case insensitive.
         */
        function validate_class_code(string $code): bool
        {
            return empty($code) ? false : preg_match('/^\w{2}\d{2}\w\d{3}$/', $code);
        }

        /**
         * Validate if the given string is valid resident card code.
         */
        function validate_resident_card(string $code): bool
        {
            return empty($code) ? false : preg_match('/^\w{2}\d{8}\w{2}$/', $code);
        }
        #endregion
    }
}
