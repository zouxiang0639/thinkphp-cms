<?php

namespace app\common\bls\goods\traits;

use app\common\bls\goods\GoodsBls;
use app\common\consts\common\CommonStatusConst;
use app\common\library\format\FormatData;
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

    protected function LoadExcelByGoods(Collection $items)
    {
        return $items->each(function ($item) {
            $item['photos'] = FormatData::photosFormatUn($item['photos']);
            $item['display'] = CommonStatusConst::SHOW;
            $item['comment'] = '';
            $item['create_time'] = date('Y-m-d H:i:s');
            $item['keywords'] = '';
            $item['description'] = '';
            $item['content'] = '';
            GoodsBls::createGoods($item);
        });
    }
}
