<?php
namespace app\common\bls\integral\validate;


use app\common\base\BaseValidate;

class IntegralGoodsValidate extends BaseValidate
{

    public function setRule()
    {
        return [
            ['title|标题', 'require'],
            ['integral|积分', 'require|number'],
            ['status|状态', 'require'],
        ];
    }

    public function setMessage()
    {
        return [
            'status.require' => '请选择状态'
        ];
    }

    public function setScene()
    {
        return [
            'create'  =>  ['title', 'integral', 'status'],
            'update'  =>  ['title', 'integral', 'status']
        ];
    }

}