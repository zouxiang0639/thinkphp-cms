<?php

namespace app\common\bls\info\traits;

use app\common\consts\extended\ExtendedTypeConst;
use think\Collection;

trait InfoTrait
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
