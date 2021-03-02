<?php

namespace POICA\controller\suica {

    use POICA\model\exception as ex;
    use POICA\model\DBAdaptor;
    use POICA\handler\PostHandler as Handler;
    use POICA\validation\Validator as Valid;

    /**
     * Suica related manipulation POST method sub-handler.
     */
    class PostHandler extends Handler
    {
        public function handle(): array
        {
            #region variable
            // should be student.
            if (isset($_SESSION['user'])) {
                $cmd = 'UPDATE Usership.Users u SET suica=$1 WHERE u.userID=$2;';
                $idn = $_SESSION['user'];
            }
            // should be teacher
            else {
                $cmd = 'UPDATE School.Teachers t SET suica=$1 WHERE t.tid=$2;';
                $idn = $_SESSION['tid'];
            }
            #endregion

            try {
                $this->result = (new DBAdaptor)->insert(
                    $cmd,
                    [$this->data['idm'], $idn]
                );

                $this->logger->append_record(
                    sprintf(
                        'Successfully register suica card with IDm [%s] to %s [%s]',
                        substr($this->data['idm'], 0, 5),
                        isset($_SESSION['user']) ? 'user' : 'teacher',
                        $idn
                    )
                );
                $this->result['status'] = 2;
            }
            #region exception
            catch (ex\RecordInsertException $rie) {
                $this->logger->append_error($rie);
                $this->result['status'] = 30;
            }
            #endregion

            return $this->result;
        }

        public function validate(): bool
        {
            $msg = '';

            if (!isset($this->data['idm']))
                $msg = 'IDm hash was not supplied.';
            elseif (!Valid::validate_suica($this->data['idm']))
                $msg = "hash started with [" . substr($this->data['idm'], 0, 5) . "] was invalid.";

            if (!empty($msg)) {
                $this->result['status'] = 14;
                $this->logger->append_record(
                    "User [{$_SESSION['user']}] attempted to register suica, but " . $msg
                );
            }

            return empty($msg);
        }
    }
}
