<?php

namespace POICA\controller\student {

    use POICA\model\exception as ex;
    use POICA\model\DBAdaptor;
    use POICA\validation\Validator as Valid;
    use POICA\handler\GetHandler as Handler;

    /**
     * Student data manipulation GET method sub-handler.
     */
    class GetHandler extends Handler
    {
        public function handle(): array
        {
            #region database
            $msg = "Fail to obtain student list for class [{$this->data['c']}] with teacher ID [{$_SESSION['tid']}]";
            $cmd = "SELECT u.sid sid, u.fname fname, u.lname lname, u.suica suica FROM usership.users u WHERE u.userid IN (SELECT s.student FROM attendance.lesson_student s WHERE lesson IN (SELECT l.lessonid FROM attendance.lessons l WHERE l.teacher = $1 AND l.code = $2));";
            #endregion

            try {
                $this->result['students'] = (new DBAdaptor)->query(
                    $cmd,
                    [$_SESSION['tid'], $this->data['c']],
                    $msg
                );
                $this->result['status'] = 1;

                $this->logger->append_record(
                    "Teacher [{$_SESSION['tid']}] obtained list of students for class [{$this->data['c']}]."
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

            if (!isset($this->data['c']))
                $msg = 'insufficient data supplied.';
            elseif (!Valid::validate_lesson_code($this->data['c']))
                $msg = "supplied class code [{$this->data['c']}] is invalid.";

            if (!empty($msg)) {
                $this->result['status'] = 14;
                $this->logger->append_record(
                    "Teacher [{$_SESSION['tid']}] attempted to obtain list of students, but " . $msg
                );
            }

            return empty($msg);
        }
    }
}
