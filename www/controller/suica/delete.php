<?php

/**
 * suica DELETE method sub-handler.
 * 
 * use session data:
 * user:    student id.
 */

namespace controller\suica;

require_once 'model/DBAdaptor.php';
require_once 'model/Handler.php';

class DeleteHandler extends \model\LocalizedDeleteHandler
{
    public function Handle(): array
    {
        try {
            // update suica hash of user
            (new \model\DBAdaptor())->update_suica_student($_SESSION['user'], "");

            $this->logger->appendRecord(
                "Success to deregister suica card from user [{$_SESSION['user']}]"
            );

            $this->respond['status'] = 4;
        } catch (\model\RecordInsertException $rie) {
            $this->logger->appendError($rie);
            $this->respond['status'] = 50;
        } finally {
            return $this->respond;
        }
    }

    public function Validate(): bool
    {
        return true;
    }
}
