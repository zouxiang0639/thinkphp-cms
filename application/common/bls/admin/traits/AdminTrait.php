<?php

namespace app\common\bls\admin\traits;

use think\Collection;

trait AdminTrait
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
