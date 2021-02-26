<?php

/**
 * form data GET method sub-handler.
 * 
 * use session data:
 * user:    student id.
 */

namespace controller\from;

require_once 'model/DBAdaptor.php';
require_once 'model/Handler.php';
require_once 'model/Logger.php';
require_once 'model/Validation.php';

class GetHandler extends \model\GetHandler
{
    /**
     * Main handling procedure.
     *
     * @return array respond full respond data.
     */
    public function Handle(): array
    {
        try {
            $this->respond['frm'] = (new \model\DBAdaptor())->obtain_form($this->data['id'], $_SESSION['user']);
            $this->respond['status'] = 1;

            $this->logger->appendRecord("[{$_SESSION['user']}] obtained application form entry [{$this->data['id']}].");
        } catch (\model\RecordNotFoundException $rnf) {
            $this->logger->appendError($rnf);
            $this->respond['status'] = 20;
        } finally {
            return $this->respond;
        }
    }

    /**
     * Validate data to be used.
     *
     * @return boolean
     */
    public function Validate(): bool
    {
        $valid = isset($this->data['id']) &&
            is_numeric($this->data['id']);

        if (!$valid) {
            $this->respond['status'] = 14;
            $this->logger->appendRecord(
                "User [{$_SESSION['user']}] attempt to obtain form data, but invalid entry id supplied."
            );
        }

        return $valid;
    }
}
