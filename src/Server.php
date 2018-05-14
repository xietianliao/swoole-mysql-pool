<?php
/**
 * User: walker
 * Date: 2018/5/10
 * Time: 23:45
 */

namespace mysqlPool;

require 'Pool.php';
require 'PoolException.php';

$server = new \swoole_server('0.0.0.0',9501,SWOOLE_BASE,SWOOLE_SOCK_TCP);
$server->set([
    'work_num' => 4
]);
$server->on('WorkerStart',function($serv,$work_id){
    Pool::getInstance()->init();
});
$server->on('connect',function ($serv,$fd){
    echo "connect \n";
});
$server->on('receive',function ($serv,$fd,$from_id,$data){
    try{
        $db = Pool::getInstance()->getConnection($serv,$fd);
    }catch (PoolException $exception){

    }
    if (!is_null($db)){
        if (is_string($db)){
            // 错误信息
            $serv->send($fd,$db);
        }
        $serv->send($fd,$db->query($data));
    }
    echo 'pool connect total:'.Pool::getInstance()->getConnectCount()."\n";
    echo 'idle count:'.Pool::getInstance()->getIdleCount()."\n";
});
$server->start();
