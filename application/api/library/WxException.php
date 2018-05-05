<?php
/**
 * Author: zhujun
 * DateTime: 2018/5/4 15:42
 */

namespace app\api\library;


class WxException extends BaseException
{
    public $code = 422;
    public $msg = '微信服务器获取信息失败';
    public $errorCode = 0;
}