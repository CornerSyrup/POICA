<?php

/**
 * Form data manipulation GET method sub-handler.
 */

namespace POICA\controller\form {

    use POICA\model\exception as ex;
    use POICA\model\DBAdaptor;
    use POICA\handler\GetHandler as Handler;

    class GetHandler extends Handler
    {
        public function handle(): array
        {
            try {
                $this->result = (new DBAdaptor)->query(
                    'SELECT a.formData frm, a.formType typ FROM Applic.Applications a WHERE a.entry=$1 AND a.applyUser=$2;',
                    [$this->data['id'], $_SESSION['user']],
                    "Fail to obtain form data with entry ID [{$this->data['id']}] and user ID [{$_SESSION['user']}]"
                )[0];
                $this->result['status'] = 1;

                $this->logger->append_record(
                    "[{$_SESSION['user']}] obtain application form entry [{$this->data['id']}]."
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

            if (!isset($this->data['id']))
                $msg = 'ID for form entry is missing.';
            elseif (!is_numeric($this->data['id']))
                $msg = 'supplied ID was invalid.';

            if (!empty($msg)) {
                $this->result['status'] = 14;
                $this->logger->append_record(
                    "User [{$_SESSION['user']}] attempt to obtain form data, but " . $msg
                );
            }

            return empty($msg);
        }
    }
}
