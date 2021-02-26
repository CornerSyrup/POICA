<?php

/**
 * form sign in POST method sub-handler.
 */

namespace controller\signin;

require_once 'model/Authentication.php';
require_once 'model/DBAdaptor.php';
require_once 'model/Handler.php';
require_once 'model/Validation.php';

use model;
use model\authentication as auth;
use model\validation as valid;

/**
 * Form sign-in handler.
 */
class PostFormHandler extends \model\PostHandler
{
    /**
     * Kind of handler, true for student, false for teacher.
     * @var boolean
     */
    private $kind;

    /**
     * Instantiate a POST Handler object for form sing-in.
     *
     * @param Logger $logger Logger.
     * @throws JsonException throw when data to be parse from php://input is invalid JSON.
     */
    public function __construct(\model\Logger $logger)
    {
        parent::__construct($logger, null);
        $this->logger->SetTag('form');

        if (strlen($this->data['usr']) == 5) {
            $this->kind =  true;
        } else if (strlen($this->data['usr'])) {
            $this->kind =  false;
        }
    }

    /**
     * Main handling procedure.
     *
     * @return array respond full respond data.
     */
    public function Handle(): array
    {
        $adapter = new model\DBAdaptor();

        try {
            try {
                if ($this->kind) {
                    $hash = $adapter->obtain_student_password($this->data['usr']);
                } else {
                    $hash = $adapter->obtain_teacher_password($this->data['usr']);
                }
            } catch (model\RecordNotFoundException $rnf) {
                throw new auth\AuthenticationException("Student [{$this->data['usr']}] was not registered", 0, $rnf);
            }

            // auth success
            if (auth\verify_password($this->data['pwd'], $hash)) {
                if ($this->kind) {
                    $_SESSION['user'] = $adapter->obtain_student_userid($this->data['usr']);
                    $_SESSION['sid'] = $this->data['usr'];
                } else {
                    $_SESSION['tid'] = $this->data['usr'];
                }

                $_SESSION['log_in'] = true;

                $this->respond['status'] = 1;
                $this->logger->appendRecord(
                    "Student [{$this->data['usr']}] logged in successfully."
                );
            }
            // auth fail
            else {
                $this->respond['status'] = 0;
                $this->logger->appendRecord(
                    "Student [{$this->data['usr']}] attempted but fail to login."
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
        $valid = isset($this->data['usr']) &&
            isset($this->data['pwd']) &&
            valid\validate_pwd($this->data['pwd']);

        if (strlen($this->data['usr']) == 5) {
            $valid = $valid && valid\validate_sid($this->data['usr']);
        } else if (strlen($this->data['usr']) == 6) {
            $valid = $valid && valid\validate_tid($this->data['usr']);
        } else {
            return false;
        }

        if (!$valid) {
            $this->respond['status'] = 13;
            $this->logger->appendRecord(
                "Sign in form data is invalid."
            );
        }

        return $valid;
    }
}
