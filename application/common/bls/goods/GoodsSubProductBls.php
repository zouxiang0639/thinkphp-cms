<?php
namespace app\common\bls\goods;

use app\common\bls\goods\model\GoodsModel;
use app\common\bls\goods\model\GoodsSubProductModel;
use app\common\bls\page\PageBls;
use app\common\consts\extended\ExtendedTypeConst;
use think\Db;

class GoodsSubProductBls
{

    public static function createSubProduct($data)
    {
        $model = new GoodsSubProductModel();
        $model->goods_id = $data['goods_id'];
        $model->type = $data['type'];
        $model->title = $data['title'];
        $model->price = $data['price'] * 100;
        $model->extend = isset($data['extend']) ? $data['extend'] : [];
        return $model->save();
    }

    public static function goodsSubProductUpdate($model, $data)
    {
        $model->goods_id = $data['goods_id'];
        $model->type = $data['type'];
        $model->title = $data['title'];
        $model->price = $data['price'] * 100;
        $model->extend = isset($data['extend']) ? $data['extend'] : [];
        return $model->save();
    }

    public static function getOneSubProduct($where)
    {
        return GoodsSubProductModel::where($where)->find();
    }

    public static function getSubProductSelect($where, $order = '')
    {
        return GoodsSubProductModel::where($where)->order($order)->select();
    }
}