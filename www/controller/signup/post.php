<?php

/**
 * sign up POST method sub-handler.
 */

namespace controller\signup;

require_once 'model/Authentication.php';
require_once 'model/DBAdaptor.php';
require_once 'model/Handler.php';
require_once 'model/Validation.php';

use model;
use model\authentication as auth;
use model\validation as valid;

use function model\authentication\get_password_hash;

/**
 * Form sign-up handler.
 */
class PostHandler extends \model\PostHandler
{
    public function Handle(): array
    {
        try {
            $this->data['yr'] = substr(date('Y'), 0, 2);
            $this->data['pwd'] = get_password_hash($this->data['pwd']);

            try {
                $adapter = new model\DBAdaptor();
                $adapter->insert_credential_student($this->data);
            } catch (model\RecordInsertException $rie) {
                throw new auth\AuthenticationException("Fail to register user with student id [{$this->data['usr']}].", 0, $rie);
            }

            $this->respond['status'] = 1;
            $this->logger->appendRecord(
                "[{$this->data['usr']}] sign up successfully."
            );
        } catch (auth\AuthenticationException $ae) {
            $this->logger->appendError($ae);
            $this->respond['status'] = 21;
        } finally {
            return $this->respond;
        }
    }

    public function Validate(): bool
    {
        $valid = isset($this->data['usr']) &&
            isset($this->data['pwd']) &&
            isset($this->data['jfn']) &&
            isset($this->data['jln']) &&
            isset($this->data['jfk']) &&
            isset($this->data['jlk']) &&
            valid\validate_sid($this->data['usr']) &&
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
