<?php
namespace app\common\bls\goods\validate;


use app\common\base\BaseValidate;

class GoodsSubProductValidate extends BaseValidate
{

    public function setRule()
    {
        return [
            ['title|标题', 'require'],
            ['price|价格', 'require|number']
        ];
    }

    public function setMessage()
    {
        return [
            'title.require' => '标题不能为空',
            'price.require' => '价格不能为空',
            'price.number' => '价格只能数字'
        ];
    }

    public function setScene()
    {
        return [
            'create'  =>  ['title', 'price'],
            'update'  =>  ['title', 'price']
        ];
    }

}