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


    public function gitKey()
    {
        dump(1);die;
    }
}