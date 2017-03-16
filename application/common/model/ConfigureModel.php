<?php
namespace app\common\model;

class ConfigureModel extends BasicModel
{
    protected $name = 'configure';

    //groups_name 组名称 读取器
    protected function getGroupsNameAttr($value,$data)
    {
        return array_get(lang('configure groups'), $data['groups']);
    }
}