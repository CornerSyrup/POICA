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
     * Create and return connection to PostgreSQL
     *
     * @return resource database connection
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
     * Interface to obtain user credential from database
     *
     * @param string $sid student id
     * @throws Exception throw when credential not found
     * @return string password hash
     */
    public static function obtain_credential(string $sid): string
    {
        $con = self::create_connection();
        $res = pg_fetch_array(pg_query($con, "SELECT Usership.obtain_pwd('${sid}');"));

        if (empty($res[0])) {
            throw new Exception('credential not found');
        }

        pg_close($con);
        return $res[0];
    }
}
