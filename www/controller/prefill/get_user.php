<?php

/**
 * Lesson data GET method sub-handler.
 */

namespace controller\prefill;

require_once 'model/DBAdaptor.php';
require_once 'model/Handler.php';

use model;

class GetUserHandler extends \model\GetHandler
{
    /**
     * Main handling procedure.
     *
     * @return array respond full respond data.
     */
    public function Handle(): array
    {
        try {
            $foo = (new \model\DBAdaptor())->obtain(
                "SELECT u.sid, u.fname, u.lname, u.fkana, u.lkana FROM usership.users u WHERE sid = $1;",
                array($_SESSION['sid']),
                "Fail to obtain user info with student ID [{$_SESSION['sid']}]"
            );

            if ($foo) {
                $this->respond['data'] = $foo;
            } else {
                throw new model\RecordNotFoundException(
                    "Fail to obtain user info with student ID [{$_SESSION['sid']}]"
                );
            }

            $this->respond['status'] = 1;

            $this->logger->appendRecord(
                "Student [{$_SESSION['sid']}] obtained user info."
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
        $valid = isset($_SESSION['sid']);

        if (!$valid) {
            $this->respond['status'] = 14;
            $this->logger->appendRecord(
                "Student attempt to obtain user info, but sid was not set in session."
            );
        }
        return $valid;
    }
}
