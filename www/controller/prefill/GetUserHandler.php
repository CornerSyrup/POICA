<?php

/**
 * Prefill data GET method sub-handler.
 */

namespace POICA\controller\prefill {

    use POICA\model\exception as ex;
    use POICA\model\DBAdaptor;
    use POICA\handler\GetHandler as Handler;

    class GetUserHandler extends Handler
    {
        public function handle(): array
        {
            try {
                $this->result['data'] = (new DBAdaptor)->query(
                    'SELECT u.sid, u.fname, u.lname, u.fkana, u.lkana FROM Usership.Users u WHERE userID=$1;',
                    [$_SESSION['user']],
                    "Fail to obtain user prefill info with user ID [{$_SESSION['user']}]"
                )[0];

                $this->result['status'] = 1;

                $this->logger->append_record(
                    "Fail to obtain user prefill info with user ID [{$_SESSION['user']}]"
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
