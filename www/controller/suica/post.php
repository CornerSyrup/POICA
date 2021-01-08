<?php

/**
 * suica POST method sub-handler.
 * 
 * use session data:
 * user:    student id.
 */

namespace controller\suica;

require_once 'model/DBAdaptor.php';
require_once 'model/Handler.php';
require_once 'model/Localizer.php';
require_once 'model/Validation.php';

use model\validation as valid;

class PostHandler extends \model\PostHandler
{
    /**
     * Instantiate a new POST Handler object.
     *
     * @param Logger $logger Logger.
     * @throws JsonException throw when data to be parse is invalid JSON.
     */
    public function __construct(\model\Logger $logger)
    {
        parent::__construct($logger, null);
        $this->data = \model\Localizer::LocalizeArray($this->data);
    }

    public function Handle(): array
    {
        try {
            // update attribute of suica
            // TODO: check if not registered before, return registered if so. 
            (new \model\DBAdaptor())->update_suica($_SESSION['user'], $this->data['idm']);

            $this->logger->appendRecord(
                "Success to register suica card with idm [{$this->data['idm']}] to user with student id [{$_SESSION['user']}]"
            );

            $this->respond['status'] = 1;
        } catch (\model\RecordInsertException $rie) {
            $this->logger->appendError($rie);
            $this->respond['status'] = 30;
        } finally {
            return $this->respond;
        }
    }

    public function Validate(): bool
    {
        $valid = isset($this->data['idm'])
            && valid\validate_suica($this->data['idm']);

        if (!$valid) {
            $this->logger->appendRecord(
                "User [{$_SESSION['user']}] attempt to register suica with code [{$this->data['idm']}] which is invalid."
            );
        }

        return $valid;
    }
}
