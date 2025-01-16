<?php

namespace Framework\Database;

use PDOException;

class Database
{
    private $pdo;

    public function __construct() {
        $this->connect();
    }

    private function connect()
    {
        try {
            $dsn = "pgsql:host=db;port=5432;dbname=db_name";
            $user = "db_user";
            $password = "db_password";
            $this->pdo = new \PDO($dsn, $user, $password, [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]);
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    public function query($queryString = "")
    {
        return $this->pdo->query($queryString);
    }

    public function prepareAndExecute(String $sql, Array $sqlParameters) {
        $sth = $this->pdo->prepare($sql, [\PDO::ATTR_CURSOR => \PDO::CURSOR_FWDONLY]);
        $sth->execute($sqlParameters);

        return $sth;
    }
}