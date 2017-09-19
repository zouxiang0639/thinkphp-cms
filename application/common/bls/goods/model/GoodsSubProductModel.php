<?php
namespace app\common\bls\goods\model;

use app\common\base\BasicModel;
use app\common\library\format\FormatMoney;

class GoodsSubProductModel extends BasicModel
{
    public $name = 'goods_subproduct';

    //字段转型
    protected $type = [
        'extend' => 'array'
    ];

    // price_format读取器
    protected function getPriceFormatAttr($value, $attr)
    {
        return FormatMoney::fen2yuan($attr['price']);
    }

}