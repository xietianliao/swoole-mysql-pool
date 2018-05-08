<?php
namespace mysqlPool;

class Pool
{
    protected $maxConn = 100; //最大连接数
    protected $minConn = 5; // 最小连接数
    protected $timeout = 60; // 连接时间
    private $conn_count = 0;
    private $isInited = false; // 是否初始化

    public function init()
    {
    }





}