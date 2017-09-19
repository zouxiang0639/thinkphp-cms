<?php
namespace app\user\controller;

use app\common\bls\cart\CartBls;

class Cart extends BasicController
{
    public function index()
    {
        $model = CartBls::getCartList(['user_id' => $this->user_id], 100);
        return $this->fetch('' , [
            'list' => $model
        ]);
    }

}