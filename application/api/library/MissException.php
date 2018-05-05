<?php
/**
 * Author: zhujun
 * DateTime: 2018/5/4 12:59
 */

namespace app\api\library;


class MissException extends BaseException
{
    public $code = 404;
    public $msg = '请求资源不存在';

}