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
         *
         * @param string $id Student ID to be validated.
         * @return boolean
         */
        static function validate_sid(string $id): bool
        {
            return preg_match('/^\d{5}$/', $id);
        }

        /**
         * Validate teacher ID.
         *
         * @param string $id Teacher ID to be validated.
         * @return boolean
         */
        static function validate_tid(string $id): bool
        {
            return preg_match('/^\d{6}$/', $id);
        }

        /**
         * Validate password.
         *
         * @param string $pwd Password to be validated.
         * @return boolean
         */
        static function validate_password(string $pwd): bool
        {
            return   preg_match('/^[A-Za-z0-9]+$/', $pwd);
        }

        /**
         * Validate suica IDm hash, which hashed with SHA256.
         *
         * @param string $hash Hash of suica IDm code.
         * @return boolean
         */
        static function validate_suica(string $hash): bool
        {
            return preg_match('/^[A-Za-z0-9]{64}$/', $hash);
        }
        #endregion
    }
}
