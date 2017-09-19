<?php

namespace app\common\bls\cart\traits;

use think\Collection;

trait CartTrait
{
    /**
     * 属性填充
     * @param Collection $items
     * @return Collection
     */
    protected function formatBanner(Collection $items)
    {
        return $items->each(function ($item) {
            $item->picture = BannerTypeConst::getDesc($item->type);
            $item->amount = BannerTypeConst::getDesc($item->type);
        });
    }
}
