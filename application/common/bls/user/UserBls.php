<?php
namespace app\common\bls\user;


use app\common\bls\user\model\UserModel;

class UserBls
{

    public static function getUserList($where = '', $limit = '')
    {
        return UserModel::where($where)->paginate($limit, input());
    }
}