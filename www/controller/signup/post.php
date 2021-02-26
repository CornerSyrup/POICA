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
            $this->data['yr'] = substr(date('Y'), 2, 2);
            $this->data['pwd'] = get_password_hash($this->data['pwd']);

            try {
                $adapter = new model\DBAdaptor();
                if (strlen($this->data['usr']) == 5) {
                    $adapter->insert_credential_student($this->data);
                } else if (strlen($this->data['usr']) == 6) {
                    $adapter->insert_credential_teacher($this->data);
                }
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
        if (!isset($this->data['usr'])) {
            return false;
        }

        $valid = isset($this->data['pwd']) &&
            isset($this->data['jfn']) &&
            isset($this->data['jln']) &&
            valid\validate_pwd($this->data['pwd']) &&
            valid\validate_jname($this->data['jfn']) &&
            valid\validate_jname($this->data['jln']);

        if (strlen($this->data['usr']) == 5) {
            $valid = $valid &&
                valid\validate_sid($this->data['usr']) &&
                isset($this->data['jfk']) &&
                isset($this->data['jlk']) &&
                valid\validate_jkana($this->data['jfk']) &&
                valid\validate_jkana($this->data['jlk']);
        } else if (strlen($this->data['usr']) == 6) {
            $valid = $valid && valid\validate_tid($this->data['usr']);
        } else {
            return false;
        }

        if (!$valid) {
            $this->respond['status'] = 13;
            $this->logger->appendRecord(
                "Sign up data is invalid."
            );
        }

        return $valid;
    }
}
