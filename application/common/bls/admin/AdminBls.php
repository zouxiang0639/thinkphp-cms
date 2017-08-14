<?php
namespace app\common\bls\Admin;

use app\common\bls\admin\model\AdminModel;
use app\common\tool\Tool;

class AdminBls
{

    public static function getAdminList($where = '', $limit = 20)
    {
        return AdminModel::where($where)->paginate($limit, '', [
            'query' => input()
        ]);
    }

    public static function createAdmin($data)
    {
        $model = new AdminModel();
        $model->admin_name = $data['admin_name'];
        $model->admin_password = $data['admin_password'];
        $model->name = $data['name'];
        $model->code = $data['code'];
        $model->sex = $data['sex'];
        $model->admin_email = $data['admin_email'];
        $model->comment = $data['comment'];
        $model->role = $data['role'];
        if ($model->save()) {
            return $model;
        }
        return false;
    }

    /**
     * @param string $where
     * @return AdminModel
     */
    public static function getOneAdmin($where = '')
    {
        return AdminModel::where($where)->find();
    }

    /**
     * 后台登录数据处理
     *
     * @param  string  $param
     * @return string
     */
    public static function login($param)
    {
        $result     = AdminModel::get(['admin_name'=>$param['admin_name']]);

        if(!empty($result)){
            $password   = Tool::get('helper')->getMd5($param['admin_password']);

            if($password === $result->admin_password && $result->login_priv == '1'){
                return $result;
            }
        }

        return false;
    }


}