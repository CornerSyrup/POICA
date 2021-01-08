<?php

/**
 * form sign in POST method sub-handler.
 */

namespace controller\signin;

require_once 'model/Authentication.php';
require_once 'model/Handler.php';
require_once 'model/Localizer.php';
require_once 'model/Validation.php';

use model\authentication as auth;
use model\validation as valid;

/**
 * Form sign-in handler.
 */
class PostFormHandler extends \model\PostHandler
{
    /**
     * Instantiate a new Form sign-in Handler object.
     *
     * @param Logger $logger Logger.
     * @param array $data form data use to sign-in.
     */
    public function __construct(\model\Logger $logger)
    {
        parent::__construct($logger, null);
        $this->data = \model\Localizer::LocalizeArray($this->data);
    }

    /**
     * Main handling procedure.
     *
     * @return array respond full respond data.
     */
    public function Handle(): array
    {
        try {
            // auth success
            if (auth\authenticate_form($this->data['sid'], $this->data['pwd'])) {
                $_SESSION['user'] = $this->data['sid'];
                $_SESSION['log_in'] = true;

                $this->respond['status'] = 1;
                $this->logger->appendRecord(
                    "[{$this->data['sid']}] logged in successfully."
                );
            }
            // auth fail
            else {
                $this->respond['status'] = 0;
                $this->logger->appendRecord(
                    "[{$_POST['sid']}] attempted but fail to login."
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
            valid\validate_sid($this->data['sid']) &&
            valid\validate_pwd($this->data['pwd']);

        if (!$valid) {
            $this->logger->appendRecord(
                "Sign in form data is invalid."
            );
        }

        return $valid;
    }
}
