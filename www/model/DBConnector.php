<?php

namespace model;

use mysqli;

class DBConnector
{
    //! uncompleted, this is a dummy function for dev
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
