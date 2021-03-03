<?php

namespace POICA\controller\auth {

    use POICA\model as model;
    use POICA\model\exception as ex;
    use POICA\authentication\Authenticator as Auth;
    use POICA\validation\Validator as Valid;
    use POICA\handler\Handler;

    /**
     * Form sign up handler.
     */
    class SignUpHandler extends Handler
    {
        #region fields
        /**
         * Kind of handling, true for student, false for teacher.
         */
        private bool $kind;
        #endregion

        public function __construct(model\Logger $logger)
        {
            parent::__construct($logger);
            $this->logger->set_tag('up');
        }

        public function handle(): array
        {
            #region variable
            $this->data['pwd'] = Auth::get_password_hash($this->data['pwd']);

            // reg student
            if ($this->kind) {
                $cmd = 'INSERT INTO Usership.Users (sid, yr, pwd, fName, lName, fKana, lKana) VALUES ($1, $2, $3, $4, $5, $6, $7);';
                $this->data['yr'] = $this->get_year();
                $params = array(
                    $this->data['usr'], $this->data['yr'], $this->data['pwd'], $this->data['jfn'], $this->data['jln'], $this->data['jfk'], $this->data['jlk']
                );
            }
            // reg teacher
            else {
                $cmd = 'INSERT INTO School.Teachers (tid, fname, lname, pwd) VALUES ($1, $2, $3, $4);';
                $params = array(
                    $this->data['usr'], $this->data['jfn'], $this->data['jln'], $this->data['pwd']
                );
            }
            #endregion

            try {
                (new model\DBAdaptor)->insert($cmd, $params);

                $this->result['status'] = 1;
                $this->logger->append_record(
                    sprintf(
                        '%s [%s] signed up successfully.',
                        $this->kind ? 'Student' : 'Teacher',
                        $this->data['usr']
                    )
                );
            }
            #region exception
            catch (ex\RecordInsertException $rie) {
                $this->logger->append_error($rie);
                $this->result['status'] = 21;
            }
            #endregion

            return $this->result;
        }

        public function validate(): bool
        {
            $msg = '';

            if (!isset($this->data['usr']))
                $msg = 'User field was empty.';
            elseif (Valid::validate_sid($this->data['usr'])) {
                $this->kind = true;
            } elseif (Valid::validate_tid($this->data['usr'])) {
                $this->kind = false;
            } else
                $msg = 'Supplied user field data was invalid.';

            // commit once first after user check
            if (!empty($msg)) return $this->fail_validation($msg);

            if (!$this->is_filled($this->kind))
                $msg = 'Insufficient data supplied.';
            elseif (!Valid::validate_password($this->data['pwd']))
                $msg = 'Invalid password supplied.';

            if (!empty($msg)) return $this->fail_validation($msg);
            else return true;
        }

        #region valid
        private function is_filled(bool $kind): bool
        {
            $ret = isset($this->data['pwd'])
                && isset($this->data['jfn'])
                && isset($this->data['jln']);

            if ($kind) {
                $ret = $ret
                    && isset($this->data['jfk'])
                    && isset($this->data['jlk']);
            }

            return $ret;
        }

        private function fail_validation(string $msg): bool
        {
            $this->result['status'] = 13;
            $this->logger->append_record(
                'Invalid data supplied for sign up.'
            );

            return false;
        }
        #endregion

        private function get_year(): string
        {
            return substr(date('Y'), 2, 2);
        }
    }
}
