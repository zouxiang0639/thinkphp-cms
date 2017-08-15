<?php

namespace app\common\bls\page\traits;

use app\common\consts\extended\ExtendedTypeConst;
use app\common\consts\page\PageTemplateConst;
use think\Collection;

trait PageTrait
{
    /**
     * 属性填充
     * @param Collection $items
     * @return Collection
     */
    protected function formatPage(Collection $items)
    {
        return $items->each(function ($item) {
            $item->templateTypeName = PageTemplateConst::getDesc($item->template_type);
            $item->templatePageName = PageTemplateConst::getDesc($item->template_page);
            $item->templateInfoName = PageTemplateConst::getDesc($item->template_info);
        });
    }
}
