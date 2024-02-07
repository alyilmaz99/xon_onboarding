<?php

class DB
{
    private static mysqli $db;

    private function __construct()
    {}

    public static function Init()
    {
        $host = "localhost";
        $dbname = "todo";
        $username = "root";
        $password = "2901";

        $mysqli = new mysqli($host, $username, $password, $dbname);
        if ($mysqli->connect_errno) {
            throw new Exception("Error connecting to database: " . $mysqli->connect_error);
        }

        self::$db = $mysqli;
    }

    public static function get()
    {

        if (!self::$db) {
            self::Init();
        }
        return self::$db;
    }
}
