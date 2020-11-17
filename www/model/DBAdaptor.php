<?php

namespace model;

use Exception;

/**
 * adaptor to database and provide insertion and query services
 */
class DBAdaptor
{
    const HOST = "ec2-54-75-246-118.eu-west-1.compute.amazonaws.com";
    const DATA = "df2smd4s7a2fc9";
    const USER = "mekizxwyldndzk";
    const PASS = "f3e23c643a8d0cda95acbd4884f0adbbc6dde213da7711e70c64e55c172e1060";

    /**
     * Create and return connection to PostgreSQL.
     *
     * @throws Exception throw when connection fail after trying twice
     * @return resource database connection.
     */
    private static function create_connection()
    {
        $cstring = sprintf('host=%s dbname=%s user=%s password=%s', self::HOST, self::DATA, self::USER, self::PASS);

        $con = pg_connect($cstring);

        // try twice
        if (!$con) {
            $con = pg_connect($cstring);

            if (!$con) {
                throw new Exception('Connection fail');
            }
        } else {
            return $con;
        }
    }

    /**
     * Interface to obtain user credential from database.
     *
     * @param string $sid student id.
     * @return string password hash.
     * @throws RecordNotFoundException throw when credential not found.
     */
    public static function obtain_credential(string $sid): string
    {
        try {
            $con = self::create_connection();
        } catch (\Throwable $th) {
            throw new Exception("Fail to connect to db server.", 0, $th);
        }

        $res = pg_fetch_array(
            pg_query($con, "SELECT Usership.obtain_pwd('${sid}');")
        );

        if (empty($res[0])) {
            throw new RecordNotFoundException("Fail to obtain credential with student ID [${sid}].");
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
    public static function obtain_suica(string $code): string
    {
        try {
            $con = self::create_connection();
        } catch (\Throwable $th) {
            throw new Exception("Fail to connect to db server.", 0, $th);
        }

        $res = pg_fetch_array(
            pg_query($con, "SELECT Usership.obtain_suica('$code')")
        );

        if (empty($res[0])) {
            throw new RecordNotFoundException("Fail to obtain credential with suica id [{$code}]");
        }

        return $res[0];
    }
}

/**
 * Exception representing record not found in database.
 */
class RecordNotFoundException extends Exception
{
    /**
     * Constructor of record not found exception.
     *
     * @param string $message not found message.
     * @param integer $code error code.
     * @param Exception $innerException internal exception which raised this exception indirectly.
     */
    public function __construct(string $message, int $code = 0, Exception $innerException = null)
    {
        parent::__construct($message, $code, $innerException);
    }
}
