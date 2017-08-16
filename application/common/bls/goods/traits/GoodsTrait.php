<?php

namespace app\common\bls\goods\traits;

use think\Collection;

trait GoodsTrait
{
    /**
     * 属性填充
     * @param Collection $items
     * @return Collection
     */
    protected function formatGoods(Collection $items)
    {
        return $items->each(function ($item) {
            $item->pageName = '';

            if($page = $item->page) {
                $item->pageName = $page->title;
            }
        });
    }
}
