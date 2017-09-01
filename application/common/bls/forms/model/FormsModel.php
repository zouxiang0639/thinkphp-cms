<?php
namespace app\common\bls\forms\model;

use app\common\base\BasicModel;

class FormsModel extends BasicModel
{
    public $name = 'forms';

    //字段转型
    protected $type = [
        'extend' => 'array'
    ];

    //关联一对一 扩展
    public function extended()
    {
        return $this->hasOne('app\common\bls\extended\model\ExtendedModel', 'extended_id', 'extended_id');
    }

}