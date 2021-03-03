<?php

namespace POICA\controller\auth {

    use POICA\model as model;
    use POICA\model\exception as ex;
    use POICA\authentication as auth;
    use POICA\validation\Validator as Valid;
    use POICA\handler\Handler;

    /**
     * Form sign in handler.
     */
    class FormSignInHandler extends Handler
    {
        #region fields
        /**
         * Kind of handling, true for student, false for teacher.
         */
        private bool $kind;
        #endregion

        public function __construct(model\Logger $logger)
        {
            parent::__construct($logger, null);
            $this->logger->set_tag('on');
        }

        public function handle(): array
        {
            #region variable
            if ($this->kind) {
                $cmd = 'SELECT u.pwd, u.userID FROM Usership.Users u WHERE u.sid=$1 ORDER BY u.yr DESC LIMIT 1;';
            }
            // for teacher
            else {
                $cmd = 'SELECT t.pwd FROM School.Teachers t WHERE t.tid = $1 LIMIT 1;';
            }
            $adapter = new model\DBAdaptor;
            $foo = $this->kind
                ? 'Student [' . $this->data['usr'] . ']'
                : 'Teacher [' . $this->data['usr'] . ']';
            #endregion

            try {
                $data = $adapter->query(
                    $cmd,
                    [$this->data['usr']],
                    sprintf(
                        'Fail to obtain credential with %s ID [%s]',
                        $this->kind ? 'student' : 'teacher',
                        $this->data['usr']
                    )
                )[0];

                if (auth\Authenticator::verify_password($this->data['pwd'], $data['pwd'])) {
                    if ($this->kind) {
                        $_SESSION['user'] = $data['userid'];
                        $_SESSION['sid'] = $this->data['usr'];
                    } else {
                        $_SESSION['tid'] = $this->data['usr'];
                    }

                    $_SESSION['log_in'] = true;

                    $this->result['status'] = 1;
                    $this->logger->append_record(
                        $foo . ' logged in successfully.'
                    );
                }
                // log in failed.
                else {
                    $this->result['status'] = 0;
                    $this->logger->append_record(
                        $foo . ' attempted to sign in, but fail.'
                    );
                }
            }
            #region exception
            catch (ex\RecordNotFoundException $rnf) {
                throw new auth\AuthenticationException(
                    $foo . ' was not registered.',
                    0,
                    $rnf
                );
            }
            #endregion

            return $this->result;
        }

        public function validate(): bool
        {
            $msg = '';

            if (!(isset($this->data['usr']) && isset($this->data['pwd'])))
                $msg = 'Data supplied was insufficient';
            elseif (!valid::validate_password($this->data['pwd']))
                $msg = 'Invalid password supplied.';
            elseif (($len = strlen($this->data['usr'])) == 5)
                $this->kind = true;
            elseif ($len == 6)
                $this->kind = false;
            else
                $msg = 'Invalid user fields value supplied.';

            if (!empty($msg)) {
                $this->result['status'] = 13;
                $this->logger->append_record($msg);
            }

            return empty($msg);
        }
    }
}
