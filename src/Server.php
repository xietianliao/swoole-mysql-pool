<?php
/**
 * User: walker
 * Date: 2018/5/10
 * Time: 23:45
 */

namespace mysqlPool;

require 'Pool.php';

$server = new \swoole_http_server('0.0.0.0','9501');
$server->set([
    'work_num' => 4,
    'daemonize' => true
]);
$server->on('WorkerStart',function ($serv,$fd){
    Pool::$instance;
});
$server->on('request',function ($request,$response){
    $db = Pool::$instance->getConnection($request,$response);
    $response->end('ok');
    Pool::$instance->recycle($db);
});
$server->start();
