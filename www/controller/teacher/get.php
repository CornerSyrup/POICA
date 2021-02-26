<?php

/**
 * teacher data GET method sub-handler.
 */

namespace controller\teacher;

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
            $this->respond['teachers'] = (new \model\DBAdaptor())->obtain_teacher_list();
            $this->respond['status'] = 1;

            $this->logger->appendRecord(
                "User [{$_SESSION['user']}] obtained list of teachers."
            );
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
        $valid = isset($_SESSION['user']);

        if (!$valid) {
            $this->respond['status'] = 14;
            $this->logger->appendRecord(
                "User attempt to obtain teacher list, but user ID was not set in session."
            );
        }
        return $valid;
    }
}
