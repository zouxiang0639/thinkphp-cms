<?php

namespace app\common\extend\traits;

use app\common\bls\info\model\InfoModel;
use app\common\bls\page\model\PageModel;
use app\common\consts\page\PageTemplateConst;
use think\Collection;
use think\Config;

trait InfoTrait
{

    /**
     * 上一页
     *
     * @param PageModel $page
     * @param InfoModel $model
     * @param array $order
     * @return InfoModel
     */
    public static function previousPage(InfoModel $model, PageModel $page, $order = [])
    {
        $order = empty($order) ? ["sort" => "asc", 'info_id' => 'asc'] : $order;
        $previousPage = InfoModel::where([
            'info_id' => ['>', $model->info_id],
            'page_id' => $model->page_id
        ])->order($order)->select();

        static::formatInfo($previousPage, $page);

        if(isset($previousPage[0])) {
            return $previousPage[0];
        }
        return false;
    }

    /**
     * 下一页
     *
     * @param InfoModel $model
     * @param PageModel $page
     * @param array $order
     * @return InfoModel
     */
    public static function nextPage(InfoModel $model, PageModel $page, $order = [])
    {
        $order = empty($order) ? ["sort" => "desc", 'info_id' => 'desc'] : $order;
        $nextPage = InfoModel::where([
            'info_id' => ['<', $model->info_id],
            'page_id' => $model->page_id
        ])->order($order)->select();

        static::formatInfo($nextPage, $page);

        if(isset($nextPage[0])) {
            return $nextPage[0];
        }
        return false;
    }


    public static function formatInfo(Collection $items, PageModel $page)
    {
        return $items->each(function ($item) use($page) {
            $templateInfo    = PageTemplateConst::getDescEn($page->template_info);
            $item->url       = url('info/'.$templateInfo, ['id'=>input('id'), 'info' => $item->info_id]);
            $item->picture   = empty($item->picture) ? Config::get('basic.default_picture') : $item->picture;
        });
    }
}
