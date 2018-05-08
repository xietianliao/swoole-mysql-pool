<?php
namespace mysqlPool;

class Pool
{
    protected $maxConn = 100; //最大连接数
    protected $minConn = 5; // 最小连接数
    protected $timeout = 60; // 连接时间

    private $poolConnNum = 0; // 连接池连接数
    private $isInited = false; // 是否初始化
    private $connections = array(); // 连接池

    public static $instance; //连接池对象单例

    private function __construct()
    {

    }

    private function __clone()
    {
        // TODO: Implement __clone() method.
    }

    public function getInstance()
    {
        if (self::$instance instanceof self){
            return self::$instance;
        }else{
            self::$instance = new self();
            return self::$instance;
        }
    }

    public function run()
    {
        if ($this->isInited == false){
            $this->init();
        }
    }

    /**
     * 初始化连接池
     */
    public function init()
    {
        $this->connections = new \SplQueue();

        for($i = 0;$i < $this->minConn;$i++){
            $db = new Db();
            $this->poolConnNum++;
            $this->connections->push($db);
        }
        $this->isInited = true;
    }





}