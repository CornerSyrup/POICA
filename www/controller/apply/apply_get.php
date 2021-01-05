<?php

/**
 * form data GET method sub-handler.
 * 
 * use session data:
 * user:    student id.
 */

namespace controller\apply;

require_once 'model/DBAdaptor.php';
require_once 'model/Logger.php';
require_once 'model/Handler.php';

class GetHandler extends \model\GetHandler
{
    /**
     * Main handling procedure.
     *
     * @return array respond full respond data.
     * @throws model\RecordNotFoundException throw when specified form not found.
     */
    public function Handle(): array
    {
        $dba = new \model\DBAdaptor();

        $this->respond['frm'] = $dba->obtain_form($this->data['id'], $_SESSION['user']);
        $this->respond['status'] = 1;

        $this->logger->appendRecord("[{$_SESSION['user']}] obtained application form entry [{$this->data['id']}].");

        return $this->respond;
    }

    /**
     * Validate data to be used.
     *
     * @return boolean
     */
    public function Validate(): bool
    {
        return is_numeric($this->data['id']);
    }
}
