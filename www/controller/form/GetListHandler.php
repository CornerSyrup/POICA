<?php

/**
 * Form data list manipulation GET method sub-handler.
 */

namespace POICA\controller\form {

    use POICA\model\exception as ex;
    use POICA\model\DBAdaptor;
    use POICA\handler\GetHandler as Handler;

    class GetListHandler extends Handler
    {
        public function handle(): array
        {
            #region database
            $cmd = 'SELECT entry id, stat status, applyDate date, formType AS type FROM Applic.Applications a WHERE a.applyUser=$1';
            #endregion

            try {
                $this->result['cat'] = (new DBAdaptor)->query(
                    $cmd,
                    [$_SESSION['user']],
                    "Fail to obtain form data list with user ID [{$_SESSION['user']}]."
                );
                $this->result['status'] = 1;

                $this->logger->append_record(
                    "User [{$_SESSION['user']}] obtain applied form list."
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
