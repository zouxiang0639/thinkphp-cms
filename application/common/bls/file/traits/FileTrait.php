<?php

namespace app\common\bls\file\traits;

use app\common\consts\file\FileTypeConst;
use think\Collection;

trait FileTrait
{
    /**
     * 属性填充
     * @param Collection $items
     * @return Collection
     */
    protected function formatFile(Collection $items)
    {
        return $items->each(function ($item) {

            $item->typeName = FileTypeConst::getDesc($item->type);
            $item->typeEnName = FileTypeConst::getEn($item->type);
        });
    }
}
