<?php
namespace app\common\bls\page\validate;


use app\common\base\BaseValidate;

class PageValidate extends BaseValidate
{

    public function setRule()
    {
        return [
            ['title|标题', 'require']
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
            'create'  =>  ['title'],
            'update'  =>  ['title']
        ];
    }

}