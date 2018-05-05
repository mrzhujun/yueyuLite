<?php

namespace app\admin\model;

use think\Model;

class Content extends Model
{
    // 表名
    protected $name = 'content';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;
    
    // 追加属性
    protected $append = [
        'status_data_text',
        'create_time_text',
        'update_time_text'
    ];
    

    
    public function getStatusDataList()
    {
        return ['0' => __('Status_data 0'),'1' => __('Status_data 1')];
    }     


    public function getStatusDataTextAttr($value, $data)
    {        
        $value = $value ? $value : $data['status_data'];
        $list = $this->getStatusDataList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getCreateTimeTextAttr($value, $data)
    {
        $value = $value ? $value : $data['create_time'];
        return is_numeric($value) ? date("Y-m-d H:i:s", $value) : $value;
    }


    public function getUpdateTimeTextAttr($value, $data)
    {
        $value = $value ? $value : $data['update_time'];
        return is_numeric($value) ? date("Y-m-d H:i:s", $value) : $value;
    }

    protected function setCreateTimeAttr($value)
    {
        return $value && !is_numeric($value) ? strtotime($value) : $value;
    }

    protected function setUpdateTimeAttr($value)
    {
        return $value && !is_numeric($value) ? strtotime($value) : $value;
    }


}
