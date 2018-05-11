<?php
/**
 * User: walker
 * Date: 2018/5/11
 * Time: 00:25
 */
$client = new swoole_client(SWOOLE_SOCK_TCP);
$client2 = new swoole_client(SWOOLE_SOCK_TCP);
if (!$client->connect('127.0.0.1', 9501, -1) || !$client2->connect('127.0.0.1', 9501, -1))
{
    exit("connect failed. Error: {$client->errCode}\n");
}
$client->send("select * from userinfo limit 1");
$client2->send("select * from userinfo");
echo $client->recv();
$client->close();
$client2->close();