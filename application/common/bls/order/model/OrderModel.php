<?php
namespace app\common\bls\order\model;

use app\common\base\BasicModel;
use app\common\library\format\FormatMoney;

class OrderModel extends BasicModel
{
    public $name = 'order';

    // amount_format 读取器
    protected function getAmountFormatAttr($value, $attr)
    {
        return FormatMoney::fen2yuan($attr['amount']);
    }

    //关联一对多 分类
    public function orderAttachment()
    {
        return $this->hasMany('app\common\bls\order\model\OrderAttachmentModel','order_id', 'order_id')->where([
            'goods_id' => $this->goods_id
        ]);
    }
}