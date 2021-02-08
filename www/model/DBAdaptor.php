<?php

namespace model;

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
     * @return array
     * @throws RecordLookUpException throw when fail to obtain data from database, with supplied error message.
     */
    public function obtain(string $command, array $params, string $errorMessage, bool $assoc = true): array
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

        // Result
        $res = $assoc ? pg_fetch_all($res) : pg_fetch_all($res, PGSQL_NUM);
        if (!$res) {
            throw new RecordLookUpException(
                $errorMessage,
                0,
                new \Exception(pg_errormessage($this->connection))
            );
        }

        return $res;
    }
    #endregion

    #region credential
    /**
     * Interface to obtain user credential from database.
     *
     * @param string $sid student id.
     * @return string password hash.
     * @throws RecordNotFoundException throw when credential not found.
     */
    public function obtain_credential(string $sid): string
    {
        $res = pg_query_params($this->connection, "SELECT Usership.obtain_pwd($1);", array($sid));

        if (!$res) {
            throw new RecordNotFoundException(
                "Fail to obtain credential with student ID [{$sid}].",
                0,
                new \Exception(pg_errormessage($this->connection))
            );
        }
        $res = pg_fetch_row($res);

        if (empty($res[0])) {
            throw new RecordNotFoundException("Fail to obtain credential with student ID [{$sid}].");
        }

        return $res[0];
    }

    /**
     * Interface to obtain user credential with suica idm code.
     *
     * @param string $code suica idm code.
     * @return string user id for the specified idm code.
     * @throws RecordNotFoundException throw when credential not found.
     */
    public function obtain_suica(string $code): string
    {
        $res = pg_query_params($this->connection, "SELECT Usership.obtain_suica($1)", array($code));

        if (!$res) {
            throw new RecordNotFoundException(
                "Fail to obtain credential with suica ID [{$code}].",
                0,
                new \Exception(pg_errormessage($this->connection))
            );
        }
        $res = pg_fetch_row($res);

        if (empty($res[0])) {
            throw new RecordNotFoundException("Fail to obtain credential with suica ID [{$code}].");
        }

        return $res[0];
    }

    /**
     * Interface to insert user credential to database.
     *
     * @param array $data array of user credential.
     * @return void
     * @throws RecordInsertException throw when insertion fail
     */
    public function insert_credential(array $data)
    {
        // suppress warning message manually
        if (!@pg_query_params(
            $this->connection,
            "CALL Usership.insert_cre($1, $2, $3, $4, $5, $6, $7)",
            array($data['sid'], $data['yr'], $data['pwd'], $data['jfn'], $data['jln'], $data['jfk'], $data['jlk'])
        )) {
            throw new RecordInsertException(pg_errormessage($this->connection));
        }
    }
    #endregion

    #region application form
    /**
     * Interface to obtain form data with entry id.
     *
     * @param integer $id form data entry id.
     * @return string form data as json string.
     * @throws RecordNotFoundException throw when form data not found with supplied entry id and user id.
     */
    public function obtain_form(int $entry, int $user): string
    {
        $msg = "Fail to obtain form data with entry id [{$entry}] and user id [{$user}]";

        // TODO: create db function to replace
        $res = $this->obtain(
            "SELECT a.formdata FROM applic.applications a WHERE a.appid=$1 AND a.applyuser in (select u.userid from usership.users u where studentid=$2 limit 1);",
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
     * Interface to insert new application form data to database.
     *
     * @param AppForm $form form data to be insert to database.
     * @return void
     * @throws RecordInsertException throw when insertion fail.
     */
    public function insert_form(int $user, app_form\AppForm $form)
    {
        // TODO: create db function to replace
        // suppress warning message manually
        if (!@pg_query_params(
            $this->connection,
            "INSERT INTO Applic.Applications (applyUser, formData) SELECT userid, $2 FROM usership.users WHERE studentid=$1",
            array($user, $form->Serialize())
        )) {
            throw new RecordInsertException(pg_errormessage($this->connection));
        }
    }

    /**
     * Interface to obtain list of applied form with student id.
     *
     * @param string $user student id in string, to prevent missing leading 0.
     * @return array
     */
    public function obtain_catalogue(string $user): array
    {
        // TODO: create db function to replace
        return $this->obtain(
            "SELECT appid id, stat \"status\", applydate \"date\" FROM applic.applications a WHERE a.applyuser in (select u.userid from usership.users u where studentid=$1 limit 1);",
            array($user),
            "Fail to obtain applied form list with [{$user}]"
        );
    }
    #endregion

    #region enrolments
    /**
     * Interface to update suica idm code for user.
     *
     * @param integer $user student id.
     * @param string $idm idm code for suica card.
     * @return void
     * @throws RecordInsertException throw when fail to insert or any problem on database connection.
     */
    public function update_suica(int $user, string $idm)
    {
        if (!@pg_query_params(
            $this->connection,
            "UPDATE usership.users u SET suica=$1 WHERE u.studentid=$2;",
            array($idm, $user)
        )) {
            throw new RecordInsertException(pg_errormessage($this->connection));
        }
    }
    #endregion
}

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
