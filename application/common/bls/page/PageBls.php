<?php
namespace app\common\bls\page;

use app\common\bls\page\model\PageModel;
use app\common\extend\traits\PageTrait;

class PageBls
{
    use PageTrait;  //方法扩展

    public static function getPageList($where = [], $limit = 20)
    {
        return PageModel::where($where)->order('page_id asc')->paginate($limit, '', [
            'query' => input()
        ]);
    }

    public static function getAllPage($where = '')
    {
        $model = new PageModel();
        return $model->where($where)->column('title', $model->primaryKey);
    }

    public static function createPage($date)
    {
        $model = new PageModel();
        $model->title = $date['title'];
        $model->template_type = $date['template_type'];
        $model->template_page = $date['template_page'];
        $model->template_info = $date['template_info'];
        $model->fields_extended_id = $date['fields_extended_id'];
        $model->data_extended_id = $date['data_extended_id'];
        $model->list_row = $date['list_row'];
        $model->comment = $date['comment'];
        $model->photos = $date['photos'];
        $model->display = $date['display'];
        $model->keywords = $date['keywords'];
        $model->description = $date['description'];
        $model->content = $date['content'];
        $model->extend = isset($date['extend']) ? $date['extend'] : '';

        return $model->save();
    }

    public static function getOnePage($where)
    {
        return PageModel::where($where)->find();
    }

    public static function getPageSelect($where, $order = '')
    {
        return PageModel::where($where)->order($order)->select();
    }
}