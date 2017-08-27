<?php
namespace app\common\library\request;

use \think\Request;

class RequestHook
{
    public static function run()
    {
        Request::hook('getUser', 'app\common\bls\user\UserBls::getUser');
        Request::hook('is_login', 'app\common\bls\user\UserBls::is_login');
    }
}