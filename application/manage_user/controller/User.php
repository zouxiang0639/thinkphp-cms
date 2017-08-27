<?php
namespace app\manage_user\controller;


use app\common\bls\user\UserBls;
use app\manage\controller\BasicController;

class User extends BasicController
{

    public function index()
    {
        $model = UserBls::getUserList();
        return $this->fetch('' ,[
            'list' => $model
        ]);
    }
}