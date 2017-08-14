<?php
namespace app\common\bls\admin\validate;


use app\common\base\BaseValidate;

class AdminValidate extends BaseValidate
{
    public function setRule()
    {
        return [
            ['admin_name|用户名', 'require|unique:admin,admin_name,'.input('id').',admin_id'],
            ['admin_email|邮箱', 'email'],
            ['admin_password|密码', 'require'],
            ['role|角色', 'require'],
            ['password|新密码', 'require'],
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
            'create'  =>  ['admin_name', 'admin_email', 'admin_password', 'role'],
            'update'  =>  ['admin_email', 'role'],
            'edit_password'  =>  ['password'],
        ];
    }

}