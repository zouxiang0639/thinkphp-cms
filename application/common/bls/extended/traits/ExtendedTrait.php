<?php

namespace app\common\bls\extended\traits;

use app\common\consts\common\CommonFormInputConst;
use app\common\consts\extended\ExtendedTypeConst;
use think\Collection;

trait ExtendedTrait
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

    /**
     * 字段属性填充
     * @param Collection $items
     * @return Collection
     */
    protected function formatExtendedField(Collection $items)
    {
        return $items->each(function ($item) {
            $item->inputTypeName = CommonFormInputConst::getDesc($item->input_type);
        });
    }
}
