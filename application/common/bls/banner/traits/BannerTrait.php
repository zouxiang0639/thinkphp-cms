<?php

namespace app\common\bls\banner\traits;

use app\common\consts\banner\BannerTypeConst;
use think\Collection;

trait BannerTrait
{
    /**
     * 属性填充
     * @param Collection $items
     * @return Collection
     */
    protected function formatBanner(Collection $items)
    {
        return $items->each(function ($item) {
            $item->typeName = BannerTypeConst::getDesc($item->type);
        });
    }
}
