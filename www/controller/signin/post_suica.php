<?php

/**
 * suica sign in POST method sub-handler.
 */

namespace controller\signin;

require_once 'model/Authentication.php';
require_once 'model/Handler.php';
require_once 'model/Validation.php';

use model\authentication as auth;
use model\validation as valid;

/**
 * Suica sign-in handler.
 */
class PostSuicaHandler extends \model\PostHandler
{
    /**
     * Instantiate a POST Handler object for suica sing-in.
     *
     * @param Logger $logger Logger.
     * @throws JsonException throw when data to be parse from php://input is invalid JSON.
     */
    public function __construct(\model\Logger $logger)
    {
        parent::__construct($logger, null);
        $this->logger->SetTag('suica');
    }

    public function Handle(): array
    {
        try {
            // auth success
            if (auth\authenticate_suica($this->data['usr'], $this->data['idm'])) {
                $_SESSION['user'] = $this->data['usr'];
                $_SESSION['log_in'] = true;

                $this->respond['status'] = 2;
                $this->logger->appendRecord(
                    "[{$this->data['usr']}] logged in successfully."
                );
            }
            // auth fail
            else {
                $this->respond['status'] = 0;
                $this->logger->appendRecord(
                    "[{$this->data['usr']}] attempted but fail to login."
                );
            }
        } catch (auth\AuthenticationException $ae) {
            $this->logger->appendError($ae);
            $this->respond['status'] = 22;
        } finally {
            return $this->respond;
        }
    }

    public function Validate(): bool
    {
        $valid = isset($this->data['usr']) &&
            isset($this->data['idm']) &&
            valid\validate_sid($this->data['usr']) &&
            valid\validate_pwd($this->data['idm']);

        if (!$valid) {
            $this->respond['status'] = 13;
            $this->logger->appendRecord(
                "Sign in suica data is invalid."
            );
        }

        return $valid;
    }
}
