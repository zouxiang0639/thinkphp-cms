<?php
namespace app\common\bls\goods\validate;


use app\common\base\BaseValidate;

class GoodsValidate extends BaseValidate
{

    public function setRule()
    {
        return [
            ['title|标题', 'require'],
            ['page_id|分类', 'require']
        ];
    }

    public function setMessage()
    {
        return [
            'page_id.require' => '请选择分类'
        ];
    }

    public function setScene()
    {
        return [
            'create'  =>  ['title', 'page_id'],
            'update'  =>  ['title', 'page_id']
        ];
    }

}