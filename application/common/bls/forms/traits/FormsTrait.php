<?php

namespace app\common\bls\forms\traits;

use think\Collection;

trait FormsTrait
{
    /**
     * 属性填充
     * @param Collection $items
     * @return Collection
     */
    protected function formatForm(Collection $items)
    {
        return $items->each(function ($item) {
            $item->extendedName = '';
            $item->extendName   = '';
            if($extended = $item->extended) {
                $item->extendedName = $extended->title;
            }
            foreach ((array)$item->extend as $value) {
                $item->extendName .= $value.',';
            }
        });
    }
}
