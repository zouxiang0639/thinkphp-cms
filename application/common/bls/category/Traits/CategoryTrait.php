<?php

namespace app\common\bls\category\Traits;

use think\Collection;

trait CategoryTrait
{
    /**
     * 属性填充
     * @param Collection $items
     * @return Collection
     */
    protected function formatNavigate(Collection $items)
    {
        return $items->each(function ($item) {

            $item->titleName = $item->title;

            if(empty($item->titleName) and ($page = $item->relationPage)) {
                $item->titleName = $page->title;
            }
        });
    }
}
