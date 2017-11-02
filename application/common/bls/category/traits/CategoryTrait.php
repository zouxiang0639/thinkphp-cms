<?php

namespace app\common\bls\category\traits;

use app\common\consts\category\CategoryBindPageConst;
use app\common\consts\page\PageTemplateConst;
use think\Collection;

trait CategoryTrait
{
    /**
     * 属性填充
     * @param Collection $items
     * @return Collection
     */
    protected function formatCategory(Collection $items)
    {
        return $items->each(function ($item) {

            $item->titleName = $item->title;
            if(empty($item->titleName)  && ($page = $item->page)) {
                $item->titleName = $page->title;
            }
            $item->url = '';
            if($item->bind_page == CategoryBindPageConst::PAGE) {
                if($page = $item->page) {

                    if($page->template_type == PageTemplateConst::INFO[0]) {
                        $item->url = url(PageTemplateConst::INFO[2], ['cid' => $page->page_id]);
                    } else if ($page->template_type == PageTemplateConst::GOODS[0]) {
                        $item->url = url(PageTemplateConst::GOODS[2], ['cid' => $page->page_id]);
                    } else {
                        $item->url = url(PageTemplateConst::PAGE[2], ['id' => $page->page_id]);
                    }
                }
            }
        });
    }
}
