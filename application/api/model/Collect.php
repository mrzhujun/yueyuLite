<?php
/**
 * Author: zhujun
 * DateTime: 2018/5/4 16:02
 */

namespace app\api\model;


class Collect extends BaseModel
{
    public function Content()
    {
        return $this->hasOne('Content','id','content_id');
    }

    public function getCreateTimeAttr($value)
    {
        return date('m月d日 H:i',$value);
    }

}