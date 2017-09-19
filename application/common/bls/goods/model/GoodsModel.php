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
        return $this->hasOne('app\common\bls\page\model\PageModel', 'page_id', 'page_id');
    }

    //关联一对多 副产品
    public function goodsSubProduct()
    {
        return $this->hasMany('app\common\bls\goods\model\GoodsSubProductModel', 'goods_id', 'goods_id');
    }

    // photos_format读取器
    protected function getPhotosFormatAttr($value, $attr)
    {
        return json_decode($attr['photos']);
    }

}