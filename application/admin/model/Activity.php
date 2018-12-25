<?php

namespace app\admin\model;

use think\Model;

class Activity extends Model
{
    // 表名
    protected $name = 'activity';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    
    // 追加属性
    protected $append = [
        'time_text',
        'status_text',
        'activity_status_text'
    ];
    

    protected static function init()
    {
        self::afterInsert(function ($row) {
            $pk = $row->getPk();
            $row->getQuery()->where($pk, $row[$pk])->update(['weigh' => $row[$pk]]);
        });
    }

    
    public function getStatusList()
    {
        return ['0' => __('Status 0'),'1' => __('Status 1')];
    }     

    public function getActivityStatusList()
    {
        return ['1' => __('Activity_status 1'),'2' => __('Activity_status 2')];
    }     


    public function getTimeTextAttr($value, $data)
    {
        $value = $value ? $value : $data['time'];
        return is_numeric($value) ? date("Y-m-d H:i:s", $value) : $value;
    }


    public function getStatusTextAttr($value, $data)
    {        
        $value = $value ? $value : $data['status'];
        $list = $this->getStatusList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getActivityStatusTextAttr($value, $data)
    {        
        $value = $value ? $value : $data['activity_status'];
        $list = $this->getActivityStatusList();
        return isset($list[$value]) ? $list[$value] : '';
    }

    protected function setTimeAttr($value)
    {
        return $value && !is_numeric($value) ? strtotime($value) : $value;
    }


}
