<?php
namespace app\common\bls\cart\validate;


use app\common\base\BaseValidate;
use app\common\consts\category\CategoryBindPageConst;

class CartValidate extends BaseValidate
{

    public function setRule()
    {
        return  [
            'product_id'  => 'require',
            'goods_id'   => 'require',
        ];
    }

    public function setMessage()
    {
        return [
            'product_id.require' => '请选择产品',
            'goods_id.requireIf' => '商品不能为空',
        ];
    }

    public function setScene()
    {
        return [
            'create'  =>  ['product_id', 'goods_id']
        ];
    }

}