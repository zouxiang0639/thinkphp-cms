<?php

namespace app\common\bls\order\traits;

use app\common\bls\goods\GoodsSubProductBls;
use app\common\library\format\FormatMoney;
use think\Collection;

trait OrderTrait
{
    /**
     * 属性填充
     * @param Collection $items
     * @return Collection
     */
    protected function formatOrder(Collection $items)
    {
        $items->amount = array_sum($items->column('amount'));
        $items->amountFormat = FormatMoney::fen2yuan($items->amount);

        // 获取第一个支付类型
        $items->pay_type = $items[0]->pay_type;

        return $items->each(function ($item) {
        });
    }
}
