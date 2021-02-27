<?php

namespace POICA\model\validation {
    class JapaneseValidator
    {
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
    }
}
