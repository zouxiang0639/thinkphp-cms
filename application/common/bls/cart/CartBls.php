<?php
namespace app\common\bls\cart;

use app\common\bls\cart\model\CartModel;

class CartBls
{

    public static function getCartList($where = '', $limit = 20)
    {
        return CartModel::where($where)->paginate($limit, '', [
            'query' => input()
        ]);
    }

    /**
     * @param string $where
     * @return CartModel
     */
    public static function getOneCart($where = '')
    {
        return CartModel::where($where)->find();
    }

    public static function createCart($model, $data)
    {
        $model = $model ? $model : new CartModel();
        $model->user_id = $data['user_id'];
        $model->goods_id = $data['goods_id'];
        $model->goods_name = $data['goods_name'];
        $model->goods_subproduct_id = $data['goods_subproduct_id'];
        $model->amount = $data['amount'];
        return $model->save();
    }

    public static function getGoodsSelect($where, $order = '')
    {
        return CartModel::where($where)->order($order)->select();
    }
}