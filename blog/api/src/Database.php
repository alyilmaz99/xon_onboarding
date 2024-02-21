<?php
namespace Api;

use PDO;

class Database
{
    private static $instance = null;

    protected string $host;
    protected string $name;
    protected string $user;
    protected string $password;
    public function __construct(
        string $host,
        string $name,
        string $user,
        string $password) {

        $this->host = $host;
        $this->name = $name;
        $this->user = $user;
        $this->password = $password;
    }

    public function getConnection(): PDO
    {
        $dsn = "mysql:host={$this->host};dbname={$this->name};charset=utf8";
        return new PDO($dsn, $this->user, $this->password, [
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_STRINGIFY_FETCHES => false,
        ]);

    }

    public static function Initialize(string $host, string $name, string $user, string $password)
    {
        self::$instance = new self(
            $host,
            $name,
            $user,
            $password
        );

        return self::$instance;
    }
    public static function getInstance()
    {
        return self::$instance;
    }
    function getDateForDatabase(string $date): string
    {
        $timestamp = strtotime($date);
        $date_formatted = date('Y-m-d H:i:s', $timestamp);
        return $date_formatted;
    }

}
