<?php
namespace app\common\bls\cart\model;

use app\common\base\BasicModel;
use app\common\library\format\FormatMoney;

class CartModel extends BasicModel
{
    public $name = 'cart';

    //字段转型
    protected $type = [
        'goods_subproduct_id' => 'array'
    ];

    // amount_format 读取器
    protected function getAmountFormatAttr($value, $attr)
    {
        return FormatMoney::fen2yuan($attr['amount']);
    }
}