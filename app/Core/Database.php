<?php

namespace App\Core;

require_once __DIR__ ."/../../config/config.php";

class Database
{
    private string $host = DB_HOST;
    private string $user = DB_USER;
    private string $password = DB_PASS;
    private string $database_name = DB_NAME;

    private \PDO $db;
    private $statement;

    public function __construct() 
    {
        $dsn = "mysql:host={$this->host};dbname={$this->database_name}";
        $options = [
            \PDO::ATTR_PERSISTENT => true,
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
        ];

        try {
            $this->db = new \PDO($dsn, $this->user, $this->password, $options);
        } catch (\PDOException $error) {
            die('' . $error->getMessage());
        }
    }

    public function query ($sql) 
    {
        $this->statement = $this->db->prepare($sql);
    }

    public function bind($param, $value, $type = null)
    {
        if ($type === null) {
            switch (true) {
                case ($value === intval($value)):
                    $type = \PDO::PARAM_INT;
                    break;
                case ($value === boolval($value)):
                    $type = \PDO::PARAM_BOOL;
                    break;
                case ($value === null):
                    $type = \PDO::PARAM_NULL;
                    break;
                default:
                    $type = \PDO::PARAM_STR;
            }
        }
        $this->statement->bindValue($param, $value, $type);
    }

    public function execute() 
    {
        $this->statement->execute();
        return $this->statement;
    }

    public function resultSet()
    {
        $this->execute();
        return $this->statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function single()
    {
        $this->execute();
        return $this->statement->fetch(\PDO::FETCH_ASSOC);
    }
}