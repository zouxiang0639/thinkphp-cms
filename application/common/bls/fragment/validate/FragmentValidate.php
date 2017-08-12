<?php
namespace app\common\bls\fragment\validate;


use app\common\base\BaseValidate;

class FragmentValidate extends BaseValidate
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