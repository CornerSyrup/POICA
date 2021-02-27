<?php

namespace POICA\validation {
    /**
     * Data validation function service provider.
     */
    class Validator
    {
        #region authentication
        /**
         * Validate student ID.
         */
        public static function validate_sid(string $id): bool
        {
            return preg_match('/^\d{5}$/', $id);
        }

        /**
         * Validate teacher ID.
         */
        public static function validate_tid(string $id): bool
        {
            return preg_match('/^\d{6}$/', $id);
        }

        /**
         * Validate password.
         */
        public static function validate_password(string $pwd): bool
        {
            return   preg_match('/^[A-Za-z0-9]+$/', $pwd);
        }

        /**
         * Validate suica IDm hash, which hashed with SHA256.
         */
        public static function validate_suica(string $hash): bool
        {
            return preg_match('/^[A-Za-z0-9]{64}$/', $hash);
        }
        #endregion

        #region form applications
        /**
         * Validate if the given string is valid class code, in form of ih12a092, case insensitive.
         */
        public static function validate_class_code(string $code): bool
        {
            return empty($code) ? false : preg_match('/^\w{2}\d{2}\w\d{3}$/', $code);
        }

        /**
         * Validate if the given string is valid resident card code.
         */
        public static function validate_resident_card(string $code): bool
        {
            return empty($code) ? false : preg_match('/^\w{2}\d{8}\w{2}$/', $code);
        }
        #endregion
    }
}
