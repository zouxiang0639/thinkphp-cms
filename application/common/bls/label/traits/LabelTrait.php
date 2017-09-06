<?php

namespace app\common\bls\label\traits;

use app\common\consts\banner\BannerTypeConst;
use app\common\consts\label\LabelTypeConst;
use think\Collection;

trait LabelTrait
{
    /**
     * 属性填充
     * @param Collection $items
     * @return Collection
     */
    protected function formatLabel(Collection $items)
    {
        return $items->each(function ($item) {
            $item->typeName = LabelTypeConst::getDesc($item->type);
        });
    }
}
