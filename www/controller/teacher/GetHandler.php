<?php

namespace POICA\controller\teacher {

    use POICA\model\exception as ex;
    use POICA\model\DBAdaptor;
    use POICA\handler\GetHandler as Handler;

    /**
     * Teacher data manipulation GET method sub-handler.
     */
    class GetHandler extends Handler
    {
        public function handle(): array
        {
            try {
                $this->result['teachers'] = (new DBAdaptor)->query(
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
            return true;
        }
    }
}
