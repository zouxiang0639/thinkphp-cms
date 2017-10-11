<?php
namespace app\common\bls\order\model;

use app\common\base\BasicModel;
use app\common\library\format\FormatMoney;

class OrderAttachmentModel extends BasicModel
{
    public $name = 'order_attachment';

    // price_format 读取器
    protected function getPriceFormatAttr($value, $attr)
    {
        return FormatMoney::fen2yuan($attr['price']);
    }
}