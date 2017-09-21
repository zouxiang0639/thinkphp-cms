<?php
namespace app\common\bls\integral;

use app\common\bls\integral\model\IntegralGoodsModel;

class IntegralGoodsBls
{
    public static function getIntegralGoodsList($where = '', $limit = 20)
    {
        return IntegralGoodsModel::where($where)
            ->order(['integral_goods_id' => 'desc'])
            ->paginate($limit, '', [
                'query' => input()
            ]);
    }

    public static function createIntegralGoods($date)
    {
        $model = new IntegralGoodsModel();
        $model->title       = $date['title'];
        $model->picture     = $date['picture'];
        $model->integral    = $date['integral'];
        $model->status      = $date['status'];
        return $model->save();
    }

    public static function IntegralGoodsUpdate(IntegralGoodsModel $model, $date)
    {
        $model->title       = $date['title'];
        $model->picture     = $date['picture'];
        $model->integral    = $date['integral'];
        $model->status      = $date['status'];
        return $model->save();
    }


    public static function getOneIntegralGoods($where)
    {
        return IntegralGoodsModel::where($where)->find();
    }

    public static function getIntegralGoodsSelect($where, $order = '')
    {
        return IntegralGoodsModel::where($where)->order($order)->select();
    }
}