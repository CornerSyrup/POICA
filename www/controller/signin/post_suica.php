<?php

/**
 * suica sign in POST method sub-handler.
 */

namespace controller\signin;

require_once 'model/Authentication.php';
require_once 'model/DBAdaptor.php';
require_once 'model/Handler.php';
require_once 'model/Validation.php';

use model;
use model\authentication as auth;
use model\DBAdaptor;
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
        $adapter = new DBAdaptor();

        try {
            try {
                $_SESSION['user'] = $adapter->obtain_suica_student($this->data['idm']);
            } catch (model\RecordNotFoundException $rnf) {
                throw new auth\AuthenticationException("Suica [{$this->data['idm']}] was not registered", 0, $rnf);
            }

            $_SESSION['sid'] = $adapter->obtain_student_id($_SESSION['user']);
            $_SESSION['log_in'] = true;

            $this->respond['status'] = 2;
            $this->logger->appendRecord(
                "[{$_SESSION['user']}] logged in successfully."
            );
        } catch (auth\AuthenticationException $ae) {
            $this->logger->appendError($ae);
            $this->respond['status'] = 22;
        } finally {
            return $this->respond;
        }
    }

    public function Validate(): bool
    {
        $valid = isset($this->data['idm']) && valid\validate_pwd($this->data['idm']);

        if (!$valid) {
            $this->respond['status'] = 13;
            $this->logger->appendRecord(
                "Sign in suica data is invalid."
            );
        }

        return $valid;
    }
}
