<?php
/**
 * Author: zhujun
 * DateTime: 2018/5/4 12:38
 */

namespace app\api\model;


class Banner extends BaseModel
{
    protected $hidden = ['create_time','update_time','id','app_id_from','type','status_data','appid'];

    public function getImageAttr($value)
    {
        return self::returnImageAttr($value);
    }
}