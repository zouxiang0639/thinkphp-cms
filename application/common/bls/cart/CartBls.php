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


}