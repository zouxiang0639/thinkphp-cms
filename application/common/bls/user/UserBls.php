<?php
namespace app\common\bls\user;


use app\common\bls\user\model\UserModel;
use app\common\consts\user\UserLoginType;
use app\common\tool\Tool;
use think\Session;

class UserBls
{
    public static $session_prefix = 'wwww';

    public static function getUserList($where = '', $limit = '')
    {
        return UserModel::where($where)->paginate($limit, input());
    }

    public static function createUser($data)
    {
        $model = new UserModel();
        $model->username    = $data['username'];
        $model->email       = $data['email'];
        $model->password    = Tool::get('helper')->getMd5($data['password']);

        if($model->save()) {
            return $model;
        }

        return false;
    }

    public static function getOneUser($where)
    {
        return UserModel::where($where)->find();
    }

    public static function getUser()
    {
        if($user = self::is_login()) {
            return self::getOneUser(['user_id' => $user['user_id']]);
        }

        return false;
    }

    public static function userLogin($name, $password, $type = 1)
    {
        $where = [];
        switch($type){
            case UserLoginType::EMAIL :
                $where['email'] = $name;
                break;
            default:
                return '没有这个登入类型';
        }

        $model = UserModel::where($where)->find();

        if(!empty($model)){
            $password = Tool::get('helper')->getMd5($password);

            if($model->password == $password) {

                //写人session
                self::setSession($model->user_id, $model->username);

                return true;
            }
        }

        return '用户不存在';
    }

    public static function editUser(UserModel $model, $data)
    {
        if(!$model->is_email){
            $model->email = $data['email'];
        }

        $model->nickname    = $data['nickname'];
        $model->sex         = $data['sex'];
        $model->birthday    = $data['birthday'];
        $model->comment     = $data['comment'];

        return $model->save();
    }

    /**
     * 检测用户是否登录
     * @return mixed
     */
    public static function is_login(){
        $user           = Session::get(self::$session_prefix.'user');
        if (empty($user)) {
            return false;
        } else {
            return  Session::get(self::$session_prefix.'user_sign') == self::data_auth_sign($user) ? $user : false;
        }
    }

    /**
     * 写入session
     * @access private static
     * @param  int      $user_id 用户ID
     * @param  string   $username 用户昵称
     * @return array
     */
    public static function setSession($user_id, $username){
        if(empty($user_id) && empty($username)){
            return false;
        }

        $user           = [
            'user_id'   => $user_id,
            'username'  => $username,
            'time'      => time()
        ];

        Session::set(self::$session_prefix.'user',$user);
        Session::set(self::$session_prefix.'user_sign',self::data_auth_sign($user));
        return true;
    }

    /**
     * 数据签名认证
     * @access private static
     * @param  array  $data 被认证的数据
     * @return string       签名
     */
    private static  function data_auth_sign($data) {
        $code = http_build_query($data); //url编码并生成query字符串
        $sign = sha1($code); //生成签名
        return $sign;
    }

    /**
     * 注销
     * @access private static
     * @return bool
     */
    public static function logout(){
        Session::delete(self::$session_prefix.'user');
        Session::delete(self::$session_prefix.'user_sign');
        return true;
    }
}