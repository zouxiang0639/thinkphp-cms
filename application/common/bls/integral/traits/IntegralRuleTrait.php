<?php

namespace app\common\bls\integral\traits;

use app\common\consts\common\CommonSwitchConst;
use app\common\consts\integral\IntegralRuleMethodConst;
use app\common\consts\integral\IntegralTypeConst;
use think\Collection;

trait IntegralRuleTrait
{
    /**
     * 属性填充
     * @param Collection $items
     * @return Collection
     */
    protected function formatIntegralRule(Collection $items)
    {
        return $items->each(function ($item) {
            $item->statusName = CommonSwitchConst::getDesc($item->status);
            $item->ruleMethodName = IntegralRuleMethodConst::getDesc($item->rule_method)."({$item->rule_method})";
        });
    }
}
