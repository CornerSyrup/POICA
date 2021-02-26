<?php

namespace model;

require 'model/apply/AppForm.php';

use model\app_form as form;

/**
 * adaptor to database and provide insertion and query services
 */
class DBAdaptor
{
    const HOST = "ec2-54-75-246-118.eu-west-1.compute.amazonaws.com";
    const DATA = "df2smd4s7a2fc9";
    const USER = "mekizxwyldndzk";
    const PASS = "f3e23c643a8d0cda95acbd4884f0adbbc6dde213da7711e70c64e55c172e1060";

    private $connection;

    /**
     * Instance Database Adapter with connection creation
     *
     * @throws Exception throw when connection fail after trying twice
     * @return resource database connection.
     */
    public function __construct()
    {
        $cstring = sprintf('host=%s dbname=%s user=%s password=%s', self::HOST, self::DATA, self::USER, self::PASS);

        // try connect
        $this->connection = pg_connect($cstring);

        if (!$this->connection) {
            // retry of connect
            $this->connection = pg_connect($cstring);

            if (!$this->connection) {
                // fail twice
                throw new \Exception('Fail to connect to database for unknown reason.');
            }
        }
    }

    #region common
    /**
     * Basic interface to obtain data from database.
     *
     * @param string $command SQL command.
     * @param array $params parameter to be used with supplied command.
     * @param string $errorMessage error message when fail to obtain.
     * @return array|boolean return result array or false on failure.
     * @throws RecordLookUpException throw when fail to obtain data from database, with supplied error message.
     */
    public function obtain(string $command, array $params, string $errorMessage, bool $assoc = true)
    {
        // Resources of result
        $res = @pg_query_params($this->connection, $command, $params);
        if (!$res) {
            throw new RecordLookUpException(
                $errorMessage,
                0,
                new \Exception(pg_errormessage($this->connection))
            );
        }

        return $assoc ? pg_fetch_all($res) : pg_fetch_all($res, PGSQL_NUM);
    }

    /**
     * Basic interface to insert data to database.
     *
     * @param string $command SQL command.
     * @param array $params parameter to be used with supplied command.
     * @return void
     * @throws RecordInsertException throw when fail to insert data to database.
     */
    public function insert(string $command, array $params)
    {
        if (!@pg_query_params($this->connection, $command, $params)) {
            throw new RecordInsertException(pg_errormessage($this->connection));
        }
    }
    #endregion

    #region student credential
    /**
     * Interface to insert user credential to database.
     *
     * @param array $data array of user credential.
     * @return void
     * @throws RecordInsertException throw when insertion fail
     */
    public function insert_credential_student(array $data)
    {
        $this->insert(
            "INSERT INTO Usership.Users (sid, yr, pwd, fName, lName, fKana, lKana) VALUES ($1, $2, $3, $4, $5, $6, $7);",
            array($data['usr'], $data['yr'], $data['pwd'], $data['jfn'], $data['jln'], $data['jfk'], $data['jlk'])
        );
    }

    /**
     * Interface to obtain user credential from database.
     *
     * @param string $sid student id.
     * @return string password hash.
     * @throws RecordNotFoundException throw when credential not found.
     */
    public function obtain_student_password(string $sid): string
    {
        $msg = "Fail to obtain credential with student ID [{$sid}].";

        $res = $this->obtain(
            "SELECT u.pwd FROM Usership.Users u WHERE u.sid = $1 ORDER BY u.yr DESC LIMIT 1;",
            array($sid),
            $msg
        );

        // check first row
        if (empty($res[0]['pwd'])) {
            throw new RecordNotFoundException($msg);
        }

        return $res[0]['pwd'];
    }

    /**
     * Interface to obtain user id from database.
     *
     * @param string $sid student id.
     * @return string user id of specified student id.
     * @throws RecordNotFoundException throw when user id not found.
     */
    public function obtain_student_userid(string $sid): string
    {
        $msg = "Fail to obtain user id with student ID [{$sid}]";

        $res = $this->obtain(
            "SELECT u.userID FROM Usership.Users u WHERE u.sid = $1 ORDER BY u.yr DESC LIMIT 1;",
            array($sid),
            $msg
        );

        // check first row
        if (empty($res[0]['userid'])) {
            throw new RecordNotFoundException($msg);
        }

        return $res[0]['userid'];
    }

    public function obtain_student_id(string $uid): string
    {
        $msg = "Fail to obtain student ID with user ID [{$uid}]";

        $res = $this->obtain(
            "SELECT u.sid FROM Usership.Users u WHERE u.userID = $1 ORDER BY u.yr DESC LIMIT 1;",
            array($uid),
            $msg
        );

        // check first row
        if (empty($res[0]['sid'])) {
            throw new RecordNotFoundException($msg);
        }

        return $res[0]['sid'];
    }

    /**
     * Interface to update suica idm code for user.
     *
     * @param integer $user Student user id.
     * @param string $idm SHA256 hash of idm code for suica card.
     * @return void
     * @throws RecordInsertException throw when fail to insert or any problem on database connection.
     */
    public function update_suica_student(int $user, string $idm)
    {
        $this->insert(
            "UPDATE Usership.Users u SET suica=$1 WHERE u.userID=$2;",
            array($idm, $user)
        );
    }

    /**
     * Interface to obtain user credential with suica idm code.
     *
     * @param string $hash SHA256 hash of suica idm code.
     * @return string user id for the specified idm code.
     * @throws RecordNotFoundException throw when credential not found.
     */
    public function obtain_suica_student(string $hash): string
    {
        $msg = "Fail to obtain credential with suica ID hash [{$hash}].";

        $res = $this->obtain(
            "SELECT u.userID FROM Usership.Users u WHERE u.suica = $1 LIMIT 1;",
            array($hash),
            $msg
        );

        if (empty($res[0]['userid'])) {
            throw new RecordNotFoundException("Fail to obtain credential with suica ID [{$hash}].");
        }

        return $res[0]['userid'];
    }
    #endregion

    #region teacher credential
    /**
     * Interface to insert teacher user credential to database.
     *
     * @param array $data array of user credential.
     * @return void
     * @throws RecordInsertException throw when insertion fail.
     */
    public function insert_credential_teacher(array $data)
    {
        $this->insert(
            "INSERT INTO School.Teachers (tid, fname, lname, pwd) VALUES ($1, $2, $3, $4);",
            array($data['usr'], $data['jfn'], $data['jln'], $data['pwd'])
        );
    }

    /**
     * Interface to obtain teacher credential from database.
     *
     * @param string $tid teacher ID.
     * @return string password hash.
     * @throws RecordNotFoundException throw when credential not found.
     */
    public function obtain_teacher_password(string $tid): string
    {
        $msg = "Fail to obtain credential with teacher ID [{$tid}]";

        $res = $this->obtain(
            "SELECT t.pwd FROM School.Teachers t WHERE t.tid = $1 LIMIT 1;",
            array($tid),
            $msg
        );

        // check data
        if (empty($res[0]['pwd'])) {
            throw new RecordNotFoundException($msg);
        }

        return $res[0]['pwd'];
    }
    #endregion

    #region application form
    /**
     * Interface to insert new application form data to database.
     *
     * @param integer $user user id of the applicant.
     * @param AppForm $form form data to be stored in database.
     * @param string $type identifier of the type of form to be inserted.
     * @throws RecordInsertException throw when insertion fail.
     * @return void
     */
    public function insert_form(int $user,  form\AppForm $form, string $type)
    {
        $this->insert(
            "INSERT INTO Applic.Applications (applyUser, formData, formType) VALUES ($1, $2, $3)",
            array($user, $form->Serialize(), $type)
        );
    }

    /**
     * Interface to obtain form data with entry id.
     *
     * @param integer $entry entry ID of the form.
     * @param integer $user user ID of the applied user.
     * @return string form data as json string.
     * @throws RecordNotFoundException throw when form data not found with supplied entry id and user id.
     */
    public function obtain_form(int $entry, int $user): string
    {
        $msg = "Fail to obtain form data with entry id [{$entry}] and user id [{$user}]";

        $res = $this->obtain(
            "SELECT a.formData FROM Applic.Applications a WHERE a.entry=$1 AND a.applyUser=$2;",
            array($entry, $user),
            $msg
        );

        // check first row
        if (empty($res[0]['formdata'])) {
            throw new RecordNotFoundException($msg);
        }

        return $res[0]['formdata'];
    }

    /**
     * Interface to obtain list of applied form with student id.
     *
     * @param string $user user ID.
     * @return array
     */
    public function obtain_catalogue(string $user): array
    {
        $msg = "Fail to obtain applied form list with [{$user}]";

        $res = $this->obtain(
            "SELECT entry id, stat \"status\", applyDate \"date\", formType \"type\" FROM Applic.Applications a WHERE a.applyUser=$1;",
            array($user),
            $msg
        );

        return $res ? $res : [];
    }
    #endregion

    #region teacher api
    public function obtain_teacher_list(): array
    {
        $msg = "Fail to obtain teacher list";

        $res = $this->obtain(
            "SELECT tid, fname, lname FROM School.Teachers;",
            [],
            $msg
        );

        return $res ? $res : [];
    }
    #endregion

    #region lesson api
    public function obtain_teacher_lesson_list(string $tid): array
    {
        $msg = "Fail to obtain list of lesson for teacher [{$tid}]";

        $cmd = "SELECT l.code code, s.total total, coalesce(a.attend, 0) attend FROM Attendance.Lessons l RIGHT OUTER JOIN ( SELECT s.lesson lesson, count(s.student) total FROM Attendance.Lesson_Student s GROUP BY s.lesson ) s ON l.lessonID = s.lesson LEFT OUTER JOIN ( SELECT a.lessonID lessonID, count(a.userID) attend FROM Attendance.AttendLog a GROUP BY a.lessonID ) a ON l.lessonID = a.lessonID WHERE l.teacher = $1;";

        $res = $this->obtain($cmd, array($tid), $msg);

        return $res ? $res : [];
    }
    #endregion

    #region Student API
    public function obtain_student_list(string $tid, string $lesson): array
    {
        $msg = "Fail to obtain student list for class [{$lesson}] with teacher ID [{$tid}]";
        
        $cmd = "SELECT u.sid sid, u.fname fname, u.lname lname, u.suica suica FROM usership.users u WHERE u.userid IN (SELECT s.student FROM attendance.lesson_student s WHERE lesson IN (SELECT l.lessonid FROM attendance.lessons l WHERE l.teacher = $1 AND l.code = $2));";

        $res = $this->obtain($cmd, array($tid, $lesson), $msg);

        return $res ? $res : [];
    }
    #endregion
}

#region Exception
/**
 * Exception representing record not found in database.
 */
class RecordNotFoundException extends \Exception
{
    /**
     * Constructor of record not found exception.
     *
     * @param string $message not found message.
     * @param integer $code error code.
     * @param Exception $innerException internal exception which raised this exception indirectly.
     */
    public function __construct(string $message, int $code = 0, \Exception $innerException = null)
    {
        parent::__construct($message, $code, $innerException);
    }
}

/**
 * Exception representing record insertion failure.
 */
class RecordInsertException extends \Exception
{
    /**
     * Constructor of record insert exception.
     *
     * @param string $message message of insertion failure.
     * @param integer $code error code
     * @param Exception $innerException internal exception which raised this exception indirectly.
     */
    public function __construct(string $message, int $code = 0, \Exception $innerException = null)
    {
        parent::__construct($message, $code, $innerException);
    }
}

/**
 * Exception representing error encountered in record look up procedure.
 */
class RecordLookUpException extends \Exception
{

    /**
     * Constructor of record look up exception.
     *
     * @param string $message error or warning message from database.
     * @param integer $code error code.
     * @param \Exception $innerException internal exception which raised this exception indirectly.
     */
    public function __construct(string $message, int $code = 0, \Exception $innerException = null)
    {
        parent::__construct("Fail to lookup record with following message:\n\t" . $message, $code, $innerException);
    }
}
#endregion
