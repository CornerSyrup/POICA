<?php

namespace POICA\controller\lesson {

    use POICA\model\exception as ex;
    use POICA\model\DBAdaptor;
    use POICA\handler\GetHandler as Handler;

    /**
     * Lesson data manipulation GET method sub-handler.
     */
    class GetHandler extends Handler
    {
        public function handle(): array
        {
            #region database
            $cmd = "SELECT l.code code, s.total total, coalesce(a.attend, 0) attend FROM Attendance.Lessons l RIGHT OUTER JOIN ( SELECT s.lesson lesson, count(s.student) total FROM Attendance.Lesson_Student s GROUP BY s.lesson ) s ON l.lessonID = s.lesson LEFT OUTER JOIN ( SELECT a.lessonID lessonID, count(a.userID) attend FROM Attendance.AttendLog a GROUP BY a.lessonID ) a ON l.lessonID = a.lessonID WHERE l.teacher = $1;";
            #endregion

            try {
                $this->result['lessons'] = (new DBAdaptor)->query(
                    $cmd,
                    [$_SESSION['tid']],
                    "Fail to obtain list of lesson for teacher [{$_SESSION['tid']}]"
                );
                $this->result['status'] = 1;

                $this->logger->append_record(
                    "Teacher [{$_SESSION['tid']}] obtained list of lessons."
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
