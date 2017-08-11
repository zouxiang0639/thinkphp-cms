<?php
namespace app\common\bls\page;

use app\common\bls\page\model\PageModel;

class PageBls
{

    public static function getPageList($where = [], $limit = 20)
    {
        return PageModel::where($where)->paginate($limit, '', [
            'query' => input()
        ]);
    }

    //关联一对一
    public function dataFieldsExtended()
    {
        return $this->hasOne('app\common\model\ExtendedModel','extended_id','data_extended_id');
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

        return $model->save();
    }

    public static function getOnePage($where)
    {
        return PageModel::where($where)->find();
    }

// 检查扩展
    public static function checkExtended($page_id)
    {
        $extended = PageModel::get($page_id)->dataFieldsExtended;

        /*//数据库扩展关联数据操作
        if($extended['group'] == 2){

            //拼接扩展的模型命名空间
            $mysqlName  = explode('_', $extended['name'].'_Model');
            $modelName  = array_map(function($arr){
                return ucfirst($arr);
            }, $mysqlName);
            $modelName  = implode('', $modelName);
            $this->extendedsModel    = "app\\manage\\model\\{$modelName}";
            unset( $this->extend);

            //关联数据一起更新
            $this->together('extendeds');
        }
*/
        return $extended;
    }

}