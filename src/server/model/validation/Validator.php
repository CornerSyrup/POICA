<?php

namespace POICA\model\validation {
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
