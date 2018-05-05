<?php

namespace app\admin\model;

use think\Model;

class App extends Model
{
    // 表名
    protected $name = 'app';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;
    
    // 追加属性
    protected $append = [
        'column_switch_text'
    ];
    

    
    public function getColumnSwitchList()
    {
        return ['0' => __('Column_switch 0'),'1' => __('Column_switch 1')];
    }     


    public function getColumnSwitchTextAttr($value, $data)
    {        
        $value = $value ? $value : $data['column_switch'];
        $list = $this->getColumnSwitchList();
        return isset($list[$value]) ? $list[$value] : '';
    }




}
