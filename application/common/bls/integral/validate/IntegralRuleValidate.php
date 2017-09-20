<?php
namespace app\common\bls\integral\validate;


use app\common\base\BaseValidate;

class IntegralRuleValidate extends BaseValidate
{

    public function setRule()
    {
        return [
            ['title|标题', 'require'],
            ['rule_method|规则方法', 'require'],
            ['integral|积分', 'require|number'],
            ['status|状态', 'require'],
        ];
    }

    public function setMessage()
    {
        return [
            'rule_method.require' => '请选择规则方法',
            'status.require' => '请选择状态'
        ];
    }

    public function setScene()
    {
        return [
            'create'  =>  ['title', 'rule_method', 'integral', 'status'],
            'update'  =>  ['title', 'page_id']
        ];
    }

}