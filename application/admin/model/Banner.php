<?php

namespace app\admin\model;

use think\Model;

class Banner extends Model
{
    // 表名
    protected $name = 'banner';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;
    
    // 追加属性
    protected $append = [
        'type_text',
        'status_data_text'
    ];
    

    
    public function getTypeList()
    {
        return ['0' => __('Type 0'),'1' => __('Type 1'),'2' => __('Type 2'),'3' => __('Type 3')];
    }     

    public function getStatusDataList()
    {
        return ['0' => __('Status_data 0'),'1' => __('Status_data 1')];
    }     


    public function getTypeTextAttr($value, $data)
    {        
        $value = $value ? $value : $data['type'];
        $list = $this->getTypeList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getStatusDataTextAttr($value, $data)
    {        
        $value = $value ? $value : $data['status_data'];
        $list = $this->getStatusDataList();
        return isset($list[$value]) ? $list[$value] : '';
    }




}
