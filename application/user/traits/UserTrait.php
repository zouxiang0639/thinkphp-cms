<?php

namespace app\common\bls\user\traits;

use think\Collection;

trait UserTrait
{
    /**
     * 属性填充
     * @param Collection $items
     * @return Collection
     */
    protected function formatInfo(Collection $items)
    {
        return $items->each(function ($item) {
            $item->pageName = '';

            if($page = $item->page) {
                $item->pageName = $page->title;
            }
        });
    }
}
