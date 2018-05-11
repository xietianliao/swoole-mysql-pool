<?php
namespace mysqlPool;

use Swoole\Mysql\Exception;

require 'Db.php';

class Pool
{
    protected $maxConn = 100; //最大连接数
    protected $minConn = 5; // 最小连接数
    protected $timeout = 60; // 连接时间
    protected $maxWaitNum = 0; // 最大等待数

    private $poolConnNum = 0; // 连接池连接数
    private $isInited = false; // 是否初始化
    private $idlePool; // 空闲连接
    private $waitQueue; // 等待队列


    public static $instance; //连接池对象单例

    private function __construct()
    {
        $this->idlePool = new \SplQueue();
        $this->waitQueue = new \SplQueue();
    }

    private function __clone()
    {
        // TODO: Implement __clone() method.
    }

    public static function getInstance()
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
    public function init()
    {
        if ($this->isInited == false) {

            for ($i = 0; $i < $this->minConn; $i++) {
                $this->addConnection();
            }
            $this->isInited = true;
        }
    }

    /**
     *  获取连接对外接口
     * @param $serv
     * @param $fd
     * @return mixed|null
     * @throws Exception
     */
    public function getConnection($serv,$fd)
    {
        if ($this->idlePool->count() == 0){
            // 空闲连接池数为0
            if ($this->poolConnNum < $this->maxConn){
                // 申请新的连接
                $this->addConnection();
            }else{
                if ($this->waitQueue->count() < $this->maxWaitNum){
                    $this->waitQueue->push(array($serv,$fd));
                }else{
                    throw new Exception('wait queue exceed');
                }
            }
        }
        $db = $this->getDbFromPool();
        return $db;
    }



    /**
     *  回收连接
     * @param $db
     */
    public function recycle($db)
    {
        $this->idlePool->push($db);
    }



    /**
     * 创建新连接
     */
    protected function addConnection()
    {
        $db = new Db();
        $this->poolConnNum++;
        $this->idlePool->push($db);
    }

    /**
     * 从连接池取出连接
     */
    protected function getDbFromPool()
    {
        $db = $this->idlePool->pop();
        if ($db->ping()){
            // 连接可用
            return $db;
        }else{
            // 销毁连接，连接数减一
            unset($db);
            $this->poolConnNum--;
            return null;
        }

    }

    public function getIdleCount()
    {
        return $this->idlePool->count();
    }

    public function getConnectCount()
    {
        return $this->poolConnNum;
    }

}