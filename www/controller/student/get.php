<?php

/**
 * teacher data GET method sub-handler.
 */

namespace controller\student;

require_once 'model/DBAdaptor.php';
require_once 'model/Handler.php';
require_once 'model/Logger.php';
require_once 'model/Validation.php';

class GetListHandler extends \model\GetHandler
{
    /**
     * Main handling procedure.
     *
     * @return array respond full respond data.
     */
    public function Handle(): array
    {
        try {
            $this->respond['students'] = (new \model\DBAdaptor())->obtain_student_list($_SESSION['tid'], strtolower($this->data['c']));
            $this->respond['status'] = 1;

            $this->logger->appendRecord(
                "Teacher [{$_SESSION['tid']}] obtained list of students for class [{$this->data['c']}]."
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
        // this request is teacher only.
        $valid = isset($_SESSION['tid']);

        if (!$valid) {
            $this->respond['status'] = 14;
            $this->logger->appendRecord(
                "Teacher attempt to obtain student list, but tid was not set in session."
            );
        }
        return $valid;
    }
}
