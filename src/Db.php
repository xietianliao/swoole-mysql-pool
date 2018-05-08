<?php
namespace mysqlPool;

class Db
{
    const DB_HOST = 'localhost';
    const DB_PORT = 3306;
    const DB_NAME = 'test';
    const DB_USER = 'root';
    const DB_PWD = 'root';

    private $conn;

    public function __construct()
    {
        require_once './config.php';
        $this->conn = new \PDO(
            sprintf("mysql:host=%s;port=%d;dbname=%s",self::DB_HOST,self::DB_PORT,self::DB_NAME),
            self::DB_USER,
            self::DB_PWD,
            array(
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8';",
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_PERSISTENT => true
            )
        );
    }

    public function query($sql,$param)
    {
        $this->conn->prepare($sql);
        return $this->conn->query($param);
    }

    public function execute($sql,$param)
    {
        $this->conn->prepare($sql);
        return $this->conn->exec($param);
    }


}