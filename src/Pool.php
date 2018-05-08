<?php
namespace mysqlPool;

use Swoole\Mysql\Exception;

class Pool
{
    protected $maxConn = 100; //最大连接数
    protected $minConn = 5; // 最小连接数
    protected $timeout = 60; // 连接时间
    protected $maxWaitNum = 100; // 最大等待数

    private $poolConnNum = 0; // 连接池连接数
    private $isInited = false; // 是否初始化
    private $idlePool; // 空闲连接
    private $busyPool; // 工作连接
    private $waitQueue; // 等待队列


    public static $instance; //连接池对象单例

    private function __construct()
    {
        $this->idlePool = new \SplQueue();
        $this->busyPool = new \SplQueue();
        $this->waitQueue = new \SplQueue();
        $this->init();
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


    /**
     * 初始化连接池
     */
    protected function init()
    {
        if ($this->isInited == false) {

            for ($i = 0; $i < $this->minConn; $i++) {
                $db = new Db();
                $this->poolConnNum++;
                $this->idlePool->push($db);
            }
            $this->isInited = true;
        }
    }


    public function getConnection($serv,$fd)
    {
        if ($this->idlePool->count() == 0){
            // 空闲连接池数为0
            if ($this->waitQueue->count() < $this->maxWaitNum){
                $this->waitQueue->push(array($serv,$fd));
            }else{
                throw new Exception('wait queue exceed');
            }
        }else{

        }
    }





}