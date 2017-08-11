<?php
namespace app\common\base;

use think\Config;
use think\Model;

class BasicModel extends Model
{
    /**
     * 数据模型主键
     */
    public $primaryKey = 'id';



    public function defaultImage($value)
    {
        if(empty($value)){
            return Config::get('basic.default_picture');
        }
        return $value;
    }

    public function gitKey()
    {
        dump(1);die;
    }
}