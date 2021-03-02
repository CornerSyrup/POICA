<?php

/**
 * Teacher data manipulation GET method sub-handler.
 */

namespace POICA\controller\teacher {

    use POICA\model\exception as ex;
    use POICA\model\DBAdaptor;
    use POICA\handler\GetHandler as Handler;

    class GetHandler extends Handler
    {
        public function handle(): array
        {
            try {
                $this->result['students'] = (new DBAdaptor)->query(
                    'SELECT tid, fname, lname FROM School.Teachers;',
                    [],
                    'Fail to obtain list of teachers.'
                );
                $this->result['status'] = 1;

                $this->logger->append_record(
                    "User [{$_SESSION['user']}] obtained list of teachers."
                );
            }
            #region exception
            catch (ex\RecordNotFoundException $rnf) {
                $this->logger->append_error($rnf);
                $this->result['status'] = 20;
            } catch (ex\RecordLookUpException $rlu) {
                $this->logger->append_error($rlu);
                $this->result['status'] = 20;
            }
            #endregion

            return $this->result;
        }

        public function validate(): bool
        {
            $msg = '';

            if (!isset($_SESSION['user']))
                $msg = 'User attempted to obtain teacher list, but user ID was not set in session.';

            if (!empty($msg)) {
                $this->result['status'] = 14;
                $this->logger->append_record($msg);
            }

            return empty($msg);
        }
    }
}
