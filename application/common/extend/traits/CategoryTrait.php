<?php

namespace app\common\extend\traits;

use app\common\bls\category\CategoryBls;
use app\common\consts\category\CategoryBindPageConst;
use app\common\consts\page\PageTemplateConst;
use think\Collection;
use app\common\library\trees\Tree;

trait CategoryTrait
{
    /**
     * 获取树形菜单
     * @return Tree
     */
    public static function getTreeMenu($menuGroup)
    {
        $model = CategoryBls::getAllCategory(['group'=>$menuGroup]);
        static::formatMenu($model);
        return (new Tree('parent_id'))->create($model, function ($object) {
            return $object->getItems();
        });
    }

    /**
     * 格式化菜单
     *
     * @param Collection $items
     * @return $this
     */
    public static function formatMenu(Collection $items)
    {
        $module = Request::instance()->module();
        $arr = ['h5'];
        $module = in_array($module, $arr) ? $module : 'index';

        return $items->each(function ($item) use ($module) {
            $item->titleName    = $item->title;
            $item->url          = $item->links;

            if(empty($item->titleName)  && ($page = $item->page)) {
                $item->titleName = $page->title;
            }

            if($item->bind_page == CategoryBindPageConst::PAGE) {
                $page = $item->page;
                $templatePage = PageTemplateConst::getDescEn($page->template_page);
                if($page->template_type == PageTemplateConst::GOODS[0]) {
                    $item->url = url($module.'/goods/'.$templatePage, ['id' => $item->category_id]);
                } else {
                    $item->url = url($module.'/category/'.$templatePage,  ['id' => $item->category_id]);
                }
            }
        });

    }


    /**
     * 检查页面
     *
     * @param int $template
     * @param $pageName
     */
    public static function checkPage($template, $pageName)
    {
        $templatePage = PageTemplateConst::getDescEn($template);
        if($templatePage != $pageName) {
            return abort('400', '没有页面');
        }
    }
}
