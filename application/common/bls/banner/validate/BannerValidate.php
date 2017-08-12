<?php
namespace app\common\bls\banner\validate;


use app\common\base\BaseValidate;

class BannerValidate extends BaseValidate
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
            'update'  =>  ['title'],
        ];
    }

}