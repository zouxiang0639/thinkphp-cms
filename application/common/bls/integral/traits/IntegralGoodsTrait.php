<?php

namespace app\common\bls\integral\traits;

use app\common\consts\common\CommonSwitchConst;
use app\common\consts\integral\IntegralRuleMethodConst;
use app\common\consts\integral\IntegralTypeConst;
use think\Collection;

trait IntegralGoodsTrait
{
    /**
     * 属性填充
     * @param Collection $items
     * @return Collection
     */
    protected function formatIntegralGoods(Collection $items)
    {
        return $items->each(function ($item) {
            $item->statusName = CommonSwitchConst::getDesc($item->status);
        });
    }
}
