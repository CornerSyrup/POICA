<?php

namespace POICA\authentication {
    class Authenticator
    {
        /**
         * Authenticate if the user has signed in.
         */
        public static function authenticate(): bool
        {
            return $_SESSION['log_in'] ?? false;
        }

        /**
         * Authenticate if the student has signed in.
         */
        public static function authenticate_student(): bool
        {
            return self::authenticate()
                && isset($_SESSION['user'])
                && isset($_SESSION['sid']);
        }

        /**
         * Authenticate if the teacher has signed in.
         */
        public static function authenticate_teacher(): bool
        {
            return self::authenticate() && isset($_SESSION['tid']);
        }

        /**
         * Sign out the user.
         */
        public static function sign_out()
        {
            unset($_SESSION['user']);
            unset($_SESSION['sid']);
            unset($_SESSION['tid']);
            unset($_SESSION['log_in']);
        }

        public static function get_password_hash(string $pwd)
        {
            return password_hash($pwd, PASSWORD_DEFAULT);
        }


        /**
         * Verify whether password is correct.
         *
         * @param string $received Password received.
         * @param string $hash Hash of the password.
         */
        public static function verify_password(string $received, string $hash): bool
        {
            return password_verify($received, $hash);
        }
    }
}
