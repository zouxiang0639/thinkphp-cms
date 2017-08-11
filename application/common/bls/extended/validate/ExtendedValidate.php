<?php
namespace app\common\bls\extended\validate;


use app\common\base\BaseValidate;
use app\common\bls\extended\ExtendedBls;

class ExtendedValidate extends BaseValidate
{

    public function setRule()
    {
        return [
            ['title|标题', 'require'],
            ['name|名称', "require|alphaDash|unique:extended,parent_id=" . input('id')."&name=".input('name')],
            ['input_type|输出类型', 'require'],
            ['mysql_fields_type|字段类型', 'require'],
            ['mysql_fields_length|字段值长度', 'regex:^\d+(\,\d+)?$' . ExtendedBls::checkFieldsType(input('mysql_fields_type'))]
        ];
    }

    public function setMessage()
    {
        return [
            'name.requireIf'    =>'名称不能为空',
            'name.alphaDash'    =>'名称只能字母组成',
            'name.unique'       =>'名称已存在',

        ];
    }

    public function setScene()
    {
        return [
            'create'  =>  ['title', 'name' => 'requireIf:type,2|unique:extended,name|alphaDash'],
            'update'  =>  ['title'],
            'fieldsTypeCreate' => ['title', 'name', 'input_type'],
            'fieldsTypeUpdate' => ['title', 'input_type'],
            'dataTypeCreate' => ['title', 'name', 'mysql_fields_length', 'mysql_fields_type', 'input_type'],
            'dataTypeUpdate' => ['title']
        ];
    }

}