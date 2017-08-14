<?php
namespace app\common\bls\configure\validate;


use app\common\base\BaseValidate;
use app\common\bls\extended\ExtendedBls;

class ConfigureValidate extends BaseValidate
{

    public function setRule()
    {
        return [
            ['title|标题', 'require'],
            ['input_type|输出类型', 'require'],
            ['configure_name|配置名称', 'require|unique:configure,configure_name,'.input('id').',configure_id']
        ];
    }

    public function setMessage()
    {
        return [
            'configure_name.unique'  =>'配置名称已存在',

        ];
    }

    public function setScene()
    {
        return [
            'create'  =>  ['title','input_type','configure_name'],
            'update'  =>  ['title']
        ];
    }

}