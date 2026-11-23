<?php

class Database
{
    private string $host;
    private string $dbname;
    private string $user;
    private string $pass;
    private string $charset;
    private ?PDO $conn = null;

    public function __construct()
    {
        $config = require __DIR__ . '/config.local.php';

        $this->host    = $config['host'];
        $this->dbname  = $config['dbname'];
        $this->user    = $config['user'];
        $this->pass    = $config['pass'];
        $this->charset = $config['charset'];
    }

    public function getConnection(): PDO
    {
        if ($this->conn === null) {
            $dsn = "mysql:host={$this->host};dbname={$this->dbname};charset={$this->charset}";

            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];

            try {
                $this->conn = new PDO($dsn, $this->user, $this->pass, $options);
            } catch (PDOException $e) {
                die('Error de conexion a la base de datos: ' . $e->getMessage());
            }
        }

        return $this->conn;
    }
}