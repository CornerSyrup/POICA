<?php

/**
 * Suica related manipulation DELETE method sub-handler.
 */

namespace POICA\controller\suica {

    use POICA\model\exception as ex;
    use POICA\model\DBAdaptor;
    use POICA\handler\DeleteHandler as Handler;

    class DeleteHandler extends Handler
    {
        public function handle(): array
        {
            #region variable
            // should be student.
            if (isset($_SESSION['user'])) {
                $cmd = 'UPDATE Usership.Users u SET suica=NULL WHERE u.userID=$1;';
                $idn = $_SESSION['user'];
            }
            // should be teacher
            else {
                $cmd = 'UPDATE School.Teachers t SET suica=NULL WHERE t.tid=$1;';
                $idn = $_SESSION['tid'];
            }
            #endregion

            try {
                $this->result = (new DBAdaptor)->insert(
                    $cmd,
                    [$idn]
                );

                $this->logger->append_record(
                    sprintf(
                        'Successfully deregister suica card from %s [%s]',
                        isset($_SESSION['user']) ? 'user' : 'teacher',
                        $idn
                    )
                );
                $this->result['status'] = 4;
            }
            #region exception
            catch (ex\RecordInsertException $rie) {
                $this->logger->append_error($rie);
                $this->result['status'] = 50;
            }
            #endregion

            return $this->result;
        }

        public function validate(): bool
        {
            return true;
        }
    }
}
