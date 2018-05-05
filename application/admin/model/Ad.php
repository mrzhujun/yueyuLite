<?php

namespace app\admin\model;

use think\Model;

class Ad extends Model
{
    // 表名
    protected $name = 'ad';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;
    
    // 追加属性
    protected $append = [
        'where_text',
        'is_open_text'
    ];
    

    
    public function getWhereList()
    {
        return ['0' => __('Where 0'),'1' => __('Where 1'),'2' => __('Where 2'),'3' => __('Where 3'),'4' => __('Where 4'),'5' => __('Where 5'),'6' => __('Where 6')];
    }     

    public function getIsOpenList()
    {
        return ['0' => __('Is_open 0'),'1' => __('Is_open 1')];
    }     


    public function getWhereTextAttr($value, $data)
    {        
        $value = $value ? $value : $data['where'];
        $list = $this->getWhereList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getIsOpenTextAttr($value, $data)
    {        
        $value = $value ? $value : $data['is_open'];
        $list = $this->getIsOpenList();
        return isset($list[$value]) ? $list[$value] : '';
    }




}
