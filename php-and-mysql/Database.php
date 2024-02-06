<?php

class Database
{
    private static $instance = null;

    protected string $host;
    protected string $name;
    protected string $user;
    protected string $password;

    private function __construct(
        string $host,
        string $name,
        string $user,
        string $password
    ) {
        $this->host = $host;
        $this->name = $name;
        $this->user = $user;
        $this->password = $password;
    }

    public static function Initialize(string $host, string $name, string $user, string $password)
    {
        if (!self::$instance) {
            self::$instance = new self(
                $host,
                $name,
                $user,
                $password
            );
        }
        return self::$instance;
    }

    public static function getInstance()
    {
        return self::$instance;
    }

    public function getConnection(): PDO
    {
        $dsn = "mysql:host={$this->host};dbname={$this->name};charset=utf8";
        return new PDO($dsn, $this->user, $this->password, [
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_STRINGIFY_FETCHES => false,
        ]);
    }
}
