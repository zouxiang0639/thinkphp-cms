<?php
namespace app\appadmin\model;

use think\Model;
use app\common\tool\Tool;

class AdminModel extends Model
{
    // 设置完整的数据表（包含前缀）
    protected $name = 'admin';

    protected $type = [
        'role' => 'array'
    ];

    //初始化属性
    protected function initialize()
    {

    }

    /**
     * 获取器 last_login_ip  long2ip 转换Ip地址
     *
     * @return string
     */
    public function getLastLoginIpAttr($value,$data){
        return long2ip($value);
    }

    /**
     * 后台登录数据处理
     *
     * @param  string  $param
     * @return string
     */
    public static function login($param)
    {
        $result     = self::get(['admin_name'=>$param['admin_name']]);

        if(!empty($result)){
            $password   = Tool::get('helper')->getMd5($param['admin_password']);
            if($password === $result->admin_password && $result->login_priv == 'Y'){
                return $result;
            }
        }

        return false;
    }

}