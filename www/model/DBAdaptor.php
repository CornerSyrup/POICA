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

    var $connection;

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

    /**
     * Interface to obtain user credential from database.
     *
     * @param string $sid student id.
     * @return string password hash.
     * @throws RecordNotFoundException throw when credential not found.
     */
    public function obtain_credential(string $sid): string
    {
        $res = pg_fetch_array(
            pg_query($this->connection, "SELECT Usership.obtain_pwd('${sid}');")
        );

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
        $res = pg_fetch_array(
            pg_query($this->connection, "SELECT Usership.obtain_suica('$code')")
        );

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
        if (!@pg_query($this->connection, "CALL Usership.insert_cre('{$data['sid']}','{$data['yr']}','{$data['pwd']}','{$data['jfn']}','{$data['jln']}','{$data['jfk']}','{$data['jlk']}')")) {
            throw new RecordInsertException(pg_errormessage($this->connection));
        }
    }
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
