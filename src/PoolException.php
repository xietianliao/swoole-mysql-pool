<?php
/**
 * Class Description: 连接池异常对象
 * User: Walker
 * Job Number: HC343
 * Date: 2018/5/14
 * Time: 15:32
 * Email: showphp@foxmail.com
 */

namespace mysqlPool;

class PoolException extends \Exception
{
    private $errorMap = array(
        // error
        1001 => '等待队列已满',
        // notice
        2001 => '已加入队列，等待申请中...',
        2002 => '获取连接已中断，'

    );

    public function __construct($code)
    {
        $message = isset($this->errorMap[$code])?$this->errorMap[$code]:'unknown error';
        parent::__construct($message, $code);
    }
}