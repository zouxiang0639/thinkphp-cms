<?php
namespace app\common\bls\goods\model;

use app\common\base\BasicModel;

class GoodsModel extends BasicModel
{
    public $name = 'goods';
    public $primaryKey = 'goods_id';

    //字段转型
    protected $type = [
        'extend' => 'array'
    ];

    //关联一对一 分类
    public function page()
    {
        return $this->hasOne('app\common\bls\page\model\PageModel','page_id', 'page_id');
    }

}