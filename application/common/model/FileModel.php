<?php
namespace app\common\model;

class FileModel extends BasicModel
{
    public $name = 'file';

    /**
     * groups读取器  别名group
     *
     * @param  int      $value
     * @param  array   $data
     * @return int
     */
    protected function getGroupAttr($value, $data)
    {
        $array = [1 => 'image', 2 => 'file', '3' => 'video', '4' => 'audio'];
        return isset($array[$data['groups']]) ? $array[$data['groups']] : '';
    }

}