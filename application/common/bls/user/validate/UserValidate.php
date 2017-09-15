<?php
namespace app\common\bls\user\validate;


use app\common\base\BaseValidate;

class UserValidate extends BaseValidate
{

    public function setRule()
    {
        $id = intval(input('id'));
        return [
            ['email|邮箱', 'require|email|unique:user,email,'.$id.',user_id|token'],
            ['username|昵称', 'require|unique:user,email,'.$id.',user_id'],
            ['password|密码', 'require|length:6,16'],
            ['password_confirm|确认密码', 'require|confirm'],
            ['old_password|原密码', 'require'],
            ['verify|验证吗', 'require|captcha'],
            ['nickname|昵称', 'require'],
            ['sex|性别', 'require'],
            ['birthday|生日', 'require'],
            ['file|头像', 'require']
        ];
    }

    public function setMessage()
    {
        return [
            'email.unique'      => '邮箱已存在',
            'email.require'     => '邮箱不能为空',
            'username.unique'   => '昵称已存在',
            'password.require'  => '密码不能为空',
            'password.length'   => '密码需要6到16个字符组成',
            'password_confirm.confirm' => '两次密码输入错误',
        ];
    }

    public function setScene()
    {
        return [
            'register'      => ['email', 'password', 'password_confirm', 'verify'], //注册
            'login'         => ['email' => 'require|email|token', 'password', 'verify'], //登入
            'basicEdit'     => ['email', 'nickname', 'sex', 'birthday'], //基本信息修改
            'setPassword'   => ['password','password_confirm'], //修改密码
            'uploadAvatar'  => ['file'], //上传头像
            'forget'        => ['email' => 'require|email|token', 'verify'], //邮箱找回密码
        ];
    }

}