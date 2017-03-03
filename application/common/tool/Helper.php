<?php
namespace app\common\tool;

use think\Config;

class Helper
{

    /**
     * 双层md5+自定义字符串加密
     *
     * @param  string  $password
     * @return string
     */
    public function getMd5($password)
    {
        $str    = Config::get('login_md5');
        return md5(md5($password).$str);
    }

}