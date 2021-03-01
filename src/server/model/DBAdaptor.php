<?php

namespace POICA\model {

    use POICA\model\exception\RecordLookUpException;

    /**
     * Database related service provider.
     */
    class DBAdaptor
    {
        #region constants
        const HOST = "ec2-54-75-246-118.eu-west-1.compute.amazonaws.com";
        const DATA = "df2smd4s7a2fc9";
        const USER = "mekizxwyldndzk";
        const PASS = "f3e23c643a8d0cda95acbd4884f0adbbc6dde213da7711e70c64e55c172e1060";
        #endregion

        #region fields
        /**
         * Connection to the database server.
         *
         * @var resource
         */
        public $connection;
        #endregion

        /**
         * Instantiate database adapter and create connection.
         */
        public function __construct()
        {
            $cstring = sprintf('host=%s dbname=%s user=%s password=%s sslmode=prefer', self::HOST, self::DATA, self::USER, self::PASS);

            $this->connection = pg_connect($cstring);

            if (!$this->connection) {
                throw new \Exception("Fail to connect to database");
            }
        }

        public function query(string $command, array $params, string $errMsg, bool $assoc = true)
        {
            $res = @pg_query_params($this->connection, $command, $params);

            if (!$res) {
                throw new RecordLookUpException($errMsg, 0, new \Exception(pg_errormessage($this->connection)));
            } else {
                if ($assoc)
                    $res = pg_fetch_all($res);
                else
                    $res = pg_fetch_all($res, PGSQL_NUM);
            }

            if (!$res) {
                $msg = pg_errormessage($this->connection);
                if (empty($msg))
                    $msg = 'No row fetched or other error encountered.';
                throw new RecordLookUpException($errMsg, 0, new \Exception($msg));
            }

            return $res;
        }
    }
}
