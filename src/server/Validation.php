<?php

namespace POICA\Validation {
    #region authentication
    function validate_sid(string $id): bool
    {
        return preg_match('/^\d{5}$/', $id);
    }

    function validate_tid(string $id): bool
    {
        return preg_match('/^\d{6}$/', $id);
    }

    function validate_password(string $pwd): bool
    {
        return   preg_match('/^[A-Za-z0-9]+$/', $pwd);
    }
    #endregion
}
