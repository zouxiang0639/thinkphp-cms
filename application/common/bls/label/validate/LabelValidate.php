<?php
namespace app\common\bls\label\validate;


use app\common\base\BaseValidate;

class LabelValidate extends BaseValidate
{
    public function setRule()
    {
        return [
            ['name|名称', 'require'],
            ['alphabet|字母', 'alpha']
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
            'create'  =>  ['title', 'alphabet'],
            'update'  =>  ['title'],
        ];
    }

}