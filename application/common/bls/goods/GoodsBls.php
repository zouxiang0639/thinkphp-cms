<?php
namespace app\common\bls\goods;

use app\common\bls\goods\model\GoodsModel;
use app\common\bls\page\PageBls;
use app\common\consts\extended\ExtendedTypeConst;
use think\Db;

class GoodsBls
{
    public static function getGoodsList($where = '', $limit = 20)
    {
        return GoodsModel::where($where)
            ->order(["sort" => "desc", 'goods_id' => 'desc'])
            ->paginate($limit, '', [
                'query' => input()
            ]);
    }

    public static function createGoods($date)
    {
        $model = new GoodsModel();
        $model->title       = $date['title'];
        $model->page_id     = $date['page_id'];
        $model->display     = $date['display'];
        $model->comment     = $date['comment'];
        $model->photos      = $date['photos'];
        $model->picture     = $date['picture'];
        $model->create_time = $date['create_time'];
        $model->keywords    = $date['keywords'];
        $model->description = $date['description'];
        $model->content     = $date['content'];
        $page = PageBls::getOnePage(['page_id'=>$model->page_id]);

        if($extended = $page->extendedData) {
            if($extended->type == ExtendedTypeConst::MYSQL) {
                Db::transaction(function() use($model, $extended, $date) {
                    $model->save();
                    Db::name($extended['name'])->insert(array_merge(['extended_id' => $model->goods_id], $date['extend']));
                });
                return true;
            }
            $model->extend = $date['extend'];
        }

        return $model->save();
    }

    public static function goodsUpdate(GoodsModel $model, $date)
    {
        $model->title       = $date['title'];
        $model->page_id     = $date['page_id'];
        $model->display     = $date['display'];
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
                Db::transaction(function() use($model, $extended, $date) {
                    $model->save();
                    Db::name($extended['name'])->where(['extended_id' => $model->goods_id])->update($date['extend']);
                });
                return true;
            }

            $model->extend = $date['extend'];
        }

        return $model->save();
    }

    public static function goodsDelete(GoodsModel $model)
    {
        $page = $model->page;
        if($extended = $page->extendedData) {
            if($extended->type == ExtendedTypeConst::MYSQL) {
                return Db::transaction(function() use($model, $extended) {
                    if($extended['check'] == true){
                        Db::name($extended['name'])->where(['extended_id' => $model->goods_id])->delete();
                    }
                    return $model->delete();
                });
            }
        }
        return $model->delete();
    }

    public static function getOneGoods($where)
    {
        return GoodsModel::where($where)->find();
    }

    public static function getGoodsMysqlExtended($tableName, $extended_id)
    {
       return Db::name($tableName)->where(['extended_id'=>$extended_id])->find();
    }

    public static function getGoodsSelect($where, $order = '')
    {
        return GoodsModel::where($where)->order($order)->select();
    }
}