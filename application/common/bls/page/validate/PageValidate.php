<?php
namespace app\common\bls\page\validate;


use app\common\base\BaseValidate;

class PageValidate extends BaseValidate
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