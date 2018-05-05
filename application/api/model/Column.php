<?php
/**
 * Author: zhujun
 * DateTime: 2018/5/4 15:00
 */

namespace app\api\model;


class Column extends BaseModel
{
    protected $hidden = ['intro'];
    public function getIcoImageAttr($value)
    {
        return self::returnImageAttr($value);
    }

}