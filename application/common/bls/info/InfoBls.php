<?php
namespace app\common\bls\info;

use app\common\bls\info\model\InfoModel;
use app\common\bls\page\PageBls;
use app\common\consts\extended\ExtendedTypeConst;
use think\Db;

class InfoBls
{
    public static function getInfoList($where = '', $limit = 20)
    {
        return InfoModel::where($where)
            ->order(["sort" => "desc", 'info_id' => 'desc'])
            ->paginate($limit, '', [
                'query' => input()
            ]);
    }

    public static function createInfo($date)
    {
        $model = new InfoModel();
        $model->title       = $date['title'];
        $model->page_id     = $date['page_id'];
        $model->display     = $date['display'];
        $model->links       = $date['links'];
        $model->visiting    = $date['visiting'];
        $model->comment     = $date['comment'];
        $model->photos      = $date['photos'];
        $model->picture     = $date['picture'];
        $model->create_time = $date['create_time'];
        $model->keywords    = $date['keywords'];
        $model->description = $date['description'];
        $model->content     = $date['content'];
        $page = PageBls::getOnePage(['page_id'=>$model->page_id]);

        if($extended = $page->extendedData) {
            $extended['check'] = false;
            if($extended->type == ExtendedTypeConst::FIELD) {
                $model->extend = $date['extend'];
            } else if($extended->type == ExtendedTypeConst::MYSQL) {
                $extended['check'] = true;
            }
        }

        Db::transaction(function() use($model, $extended, $date) {
            $model->save();
            if($extended['check'] == true){
                Db::name($extended['name'])->insert(array_merge(['extended_id' => $model->info_id], $date['extend']));
            }

        });

        return $model;
    }

    public static function infoUpdate(InfoModel $model, $date)
    {
        $model->title       = $date['title'];
        $model->page_id     = $date['page_id'];
        $model->display     = $date['display'];
        $model->links       = $date['links'];
        $model->visiting    = $date['visiting'];
        $model->comment     = $date['comment'];
        $model->photos      = $date['photos'];
        $model->picture     = $date['picture'];
        $model->create_time = $date['create_time'];
        $model->keywords    = $date['keywords'];
        $model->description = $date['description'];
        $model->content     = $date['content'];

        $page = $model->page;

        //数据扩展类型 事务更新
        if($extended = $page->extendedData) {
            if($extended->type == ExtendedTypeConst::MYSQL) {
                return Db::transaction(function() use($model, $extended, $date) {
                    $result =  $model->save();
                    Db::name($extended['name'])->where(['extended_id' => $model->info_id])->update($date['extend']);
                    return $result;
                });
            }
        }

        $model->extend = $date['extend'];
        return $model->save();
    }

    public static function infoDelete(InfoModel $model)
    {
        $page = $model->page;
        if($extended = $page->extendedData) {
            $extended['check'] = false;

            if($extended->type == ExtendedTypeConst::MYSQL) {
                $extended['check'] = true;
            }
        }

       return Db::transaction(function() use($model, $extended) {
            if($extended['check'] == true){
                Db::name($extended['name'])->where(['extended_id' => $model->info_id])->delete();
            }
           return $model->delete();
        });
    }

    public static function getOneInfo($where)
    {
        return InfoModel::where($where)->find();
    }

    public static function getInfoMysqlExtended($tableName, $extended_id)
    {
       return Db::name($tableName)->where(['extended_id'=>$extended_id])->find();
    }
}