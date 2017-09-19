<?php

namespace app\common\bls\cart\traits;

use app\common\bls\goods\GoodsSubProductBls;
use think\Collection;

trait CartTrait
{
    /**
     * 属性填充
     * @param Collection $items
     * @return Collection
     */
    protected function formatCart(Collection $items)
    {
        return $items->each(function ($item) {
            $item->subproduct =  GoodsSubProductBls::getSubProductSelect(['goods_subproduct_id' => ['in', $item['goods_subproduct_id']]]);
        });
    }
}
