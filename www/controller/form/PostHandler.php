<?php

/**
 * Form data manipulation POST method sub-handler.
 */

namespace POICA\controller\form {

    use POICA\model as model;
    use POICA\model\exception as ex;
    use POICA\apply as apply;
    use POICA\handler\PostHandler as Handler;

    class PostHandler extends Handler
    {
        #region fields
        /**
         * Form object to be used in handling.
         */
        private apply\BaseForm $form;
        #endregion

        public function __construct(model\Logger $logger)
        {
            parent::__construct($logger);

            switch ($this->data['typ']) {
                case 'doc':
                    $this->form = new apply\docissue\DocIssue;
                    break;
                default:
                    throw new \UnexpectedValueException(
                        "Unexpected application type identifier [{$this->data['typ']}]."
                    );
            }
        }

        public function handle(): array
        {
            if (empty($this->result)) {
                $this->form->deserialize(
                    model\Helper::json_stringify($this->data['frm'])
                );
            }

            try {
                (new model\DBAdaptor)->insert(
                    'INSERT INTO Applic.Applications (applyUser, formData, formType) VALUES($1, $2, $3);',
                    array(
                        $_SESSION['user'], $this->form->serialize(), $this->data['typ']
                    )
                );

                $this->result['status'] = 2;
                $this->logger->append_record(
                    "Apply of type [{$this->data['typ']}] for user [{$_SESSION['user']}] has been done."
                );
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
            $valid = false;

            try {
                $valid = $this->form::validate($this->data['frm']);

                if (!$valid) {
                    $this->result['status'] = 14;
                    $this->logger->append_record(
                        "User [{$_SESSION['user']}] attempted to apply form, but supplied form data was invalid."
                    );
                }
            } catch (apply\FormIncompleteException $fie) {
                $this->logger->append_error($fie);
                $this->result['status'] = 31;
            }

            return $valid;
        }
    }
}
