<?php
/**
 * Author: zhujun
 * DateTime: 2018/5/4 13:01
 */

namespace app\api\model;


class Content extends BaseModel
{
    protected $hidden = ['update_time','status_data','appid'];

    public function getCoverImageAttr($value)
    {
        return add_url($value);
    }

    public function getContentAttr($value)
    {
        return self::returnContentAttr($value);
    }

    public function getCreateTimeAttr($value)
    {
        return date('m月d日 H:i',$value);
    }
}