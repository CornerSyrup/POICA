<?php

namespace POICA\controller\auth {

    use POICA\model as model;
    use POICA\model\exception as ex;
    use POICA\authentication as auth;
    use POICA\validation\Validator as Valid;
    use POICA\handler\Handler;

    /**
     * Suica sign in handler.
     */
    class SuicaSignInHandler extends Handler
    {
        public function __construct(model\Logger $logger)
        {
            parent::__construct($logger, null);
            $this->logger->set_tag('suica');
        }


        public function handle(): array
        {
            $adapter = new model\DBAdaptor;
            try {
                // try students first
                try {
                    $data = $adapter->query(
                        'SELECT u.userID, u.sid FROM Usership.Users u WHERE u.suica=$1;',
                        [$this->data['idm']],
                        'Fail to obtain credential from student user.'
                    )[0];

                    $_SESSION['user'] = $data['userid'];
                    $_SESSION['sid'] = $data['sid'];

                    $this->logger->append_record(
                        "Student [{$_SESSION['sid']}] logged in successfully."
                    );
                }
                // fail on student, try teacher
                catch (ex\RecordNotFoundException $rnf) {
                    $_SESSION['tid'] = $adapter->query(
                        'SELECT t.tid FROM School.Teachers t WHERE t.suica=$1',
                        [$this->data['idm']],
                        'Fail to obtain credential from teacher.'
                    )[0]['tid'];

                    $this->logger->append_record(
                        "Teacher [{$_SESSION['tid']}] logged in successfully."
                    );
                }
            }
            #region exception
            catch (ex\RecordNotFoundException $rnf) {
                throw new auth\AuthenticationException(
                    'Suica [' . substr($this->data['idm'], 0, 5)
                        . '] was not registered.',
                    0,
                    $rnf
                );
            }
            #endregion

            $_SESSION['log_in'] = true;
            $this->result['status'] = 2;

            return $this->result;
        }

        public function validate(): bool
        {
            $msg = '';

            if (!isset($this->data['idm']))
                $msg = 'Insufficient data supplied.';
            elseif (!Valid::validate_suica($this->data['idm']))
                $msg = 'Supplied IDm hash was invalid.';

            if (!empty($msg)) {
                $this->result['status'] = 13;
                $this->logger->append_record($msg);
            }

            return empty($msg);
        }
    }
}
