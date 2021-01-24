<?php

/**
 * sign up POST method sub-handler.
 */

namespace controller\signup;

require_once 'model/Authentication.php';
require_once 'model/Handler.php';
require_once 'model/Validation.php';

use model\authentication as auth;
use model\validation as valid;

/**
 * Form sign-up handler.
 */
class PostHandler extends \model\PostHandler
{
    public function Handle(): array
    {
        try {        // enrol success
            if (auth\enrol($this->data)) {
                $this->respond['status'] = 1;
                $this->logger->appendRecord(
                    "[{$this->data['sid']}] sign up successfully."
                );
            }
            // enrol fail
            else {
                $this->respond['status'] = 0;
                $this->logger->appendRecord(
                    "Fail to enrol user with student id [{$this->data['sid']}]"
                );
            }
        } catch (auth\AuthenticationException $ae) {
            $this->logger->appendError($ae);
            $this->respond['status'] = 21;
        } finally {
            return $this->respond;
        }
    }

    public function Validate(): bool
    {
        $valid = isset($this->data['sid']) &&
            isset($this->data['pwd']) &&
            isset($this->data['jfn']) &&
            isset($this->data['jln']) &&
            isset($this->data['jfk']) &&
            isset($this->data['jlk']) &&
            valid\validate_sid($this->data['sid']) &&
            valid\validate_pwd($this->data['pwd']) &&
            valid\validate_jname($this->data['jfn']) &&
            valid\validate_jname($this->data['jln']) &&
            valid\validate_jkana($this->data['jfk']) &&
            valid\validate_jkana($this->data['jlk']);

        if (!$valid) {
            $this->respond['status'] = 13;
            $this->logger->appendRecord(
                "Sign up data is invalid."
            );
        }

        return $valid;
    }
}
