<?php

namespace model;

use mysqli;

class DBAdaptor
{
    const HOST = "ec2-54-75-246-118.eu-west-1.compute.amazonaws.com";
    const DATA = "df2smd4s7a2fc9";
    const USER = "mekizxwyldndzk";
    const PASS = "f3e23c643a8d0cda95acbd4884f0adbbc6dde213da7711e70c64e55c172e1060";

    /**
     * Create and return connection to PostgreSQL
     *
     * @return resource|false database connection
     */
    private static function create_connection()
    {
        return pg_connect(sprintf('host=%s dbname=%s user=%s password=%s', self::HOST, self::DATA, self::USER, self::PASS));
    }

    /**
     * Undocumented function
     *
     * @param string $sid student id
     * @return string password hash
     */
    public static function obtain_credential(string $sid): string
    {
        $con = new mysqli();
        $res = mysqli_query()->fetch_assoc();

        return $res['password'];
    }

    /**
     * Undocumented function
     *
     * @param string $sid student id
     * @param string $pwd_hash password hash
     * @return boolean whether insertion succeed
     */
    public static function insert_credential(string $sid, string $pwd_hash): bool
    {
        $con = new mysqli();
        return mysqli_query() ? true : false;
    }
}