<?php

namespace app\common\bls\page\traits;

use app\common\consts\extended\ExtendedTypeConst;
use think\Collection;

trait PageTrait
{
    /**
     * 属性填充
     * @param Collection $items
     * @return Collection
     */
    protected function formatExtended(Collection $items)
    {
        return $items->each(function ($item) {
            $item->typeName = ExtendedTypeConst::getDesc($item->type);
        });
    }
}
