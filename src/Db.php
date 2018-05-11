<?php
namespace mysqlPool;

class Db
{
    const DB_HOST = 'mysql';
    const DB_PORT = 3306;
    const DB_NAME = 'test';
    const DB_USER = 'root';
    const DB_PWD = 'root';

    private $conn;

    public function __construct()
    {
        $this->conn = new \PDO(
            sprintf("mysql:host=%s;port=%d;dbname=%s;charset=utf8;",self::DB_HOST,self::DB_PORT,self::DB_NAME),
            self::DB_USER,
            self::DB_PWD,
            array(
                \PDO::ATTR_PERSISTENT => true
            )
        );
    }

    public function query($sql)
    {
        return json_encode($this->conn->query($sql));
    }

    public function execute($sql,$param)
    {
        $this->conn->prepare($sql);
        return $this->conn->exec($param);
    }

    /**
     *  ping连接
     * @return bool
     */
    public function ping()
    {
        try{
            $this->conn->getAttribute(\PDO::ATTR_SERVER_INFO);
        } catch (\PDOException $e) {
            if(strpos($e->getMessage(), 'MySQL server has gone away')!==false){
                return false;
            }
        }
        return true;
    }


}