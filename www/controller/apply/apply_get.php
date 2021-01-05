<?php

/**
 * form data GET method sub-handler.
 * 
 * take GET data:
 * id:      application form data entry id.
 * 
 * use session data:
 * user:    student id.
 * 
 * set $res from invoker:
 * frm:     form data.
 */

namespace controller\apply;

require_once 'model/DBAdaptor.php';
require_once 'model/Logger.php';
require_once 'model/Handler.php';

class GetHandler extends \model\GetHandler
{
    /**
     * Undocumented function
     *
     * @return array
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

    public function Validate(): bool
    {
        return is_numeric($this->data['id']);
    }
}
