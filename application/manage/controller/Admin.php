<?php
namespace app\manage\controller;

use app\common\bls\Admin\AdminBls;
use app\common\tool\Tool;
use thinkcms\auth\model\AuthRole;
use thinkcms\auth\model\AuthRoleUser;

class Admin extends BasicController
{
    protected $id   = 0;

    public function __construct()
    {

        parent::__construct();
        $this->id       = !empty($this->request->param('id'))?intval($this->request->param('id')):$this->id;
        $nav = [
            '管理员列表' =>['url'=>'index'],
            '管理员增加' =>['url'=>'add'],
            '管理员修改' =>['url'=>['edit', ['id' => $this->id]], 'style'=>"display: none;"],
        ];
        $this->assign('navTabs',  parent::navTabs($nav));
    }

    //首页
    public function index()
    {
        $model   = AdminBls::getAdminList();

        return $this->fetch('',[
            'list'  => $model,
        ]);
    }

    //增加
    public function add()
    {
        //add_post 数据处理
        if($this->request->isPost()){
            $post = $this->request->post();

            //数据验证
            $result = $this->validate($post, 'app\common\bls\admin\validate\AdminValidate.create');
            if(true !== $result){
                // 验证失败 输出错误信息
                return $this->error($result);
            }

            //写入数据库
            $post['admin_password'] = Tool::get('helper')->getMd5($post['admin_password']);
            $model = AdminBls::createAdmin($post);

            if($model){

                //加入角色
                $authRoleUser = new AuthRoleUser();
                $authRoleUser->authRoleUserAdd($post['role'], $model['admin_id']);

                return $this->success(lang('Add success'), url('index'));
            }else{
                return $this->error(lang('Add failed'));
            }
        }

        //页面渲染
        $info['role_html'] = self::role();

        return $this->fetch('admin', [
            'info' => $info
        ]);
    }

    //编辑
    public function edit()
    {
        $model = AdminBls::getOneAdmin(['admin_id' => $this->id]);
        if(empty($model)){
          return $this->error('参数错误');
        }

        $model['role_html'] = self::role($model['role']);
        return $this->fetch('admin', [
            'info' => $model
        ]);
    }

    //修改数据
    public function editPost()
    {

        if(!empty($this->id) && $this->request->isPost()){
            $post   = $this->request->post();

            //数据验证
            $result = $this->validate($post, 'app\common\bls\admin\validate\AdminValidate.update');
            if(true !== $result){
                // 验证失败 输出错误信息
                return $this->error($result);
            }

            //修改数据
            $model   = AdminBls::getOneAdmin(['admin_id' => $this->id]);
            if($model->save($post)){

                //加入角色
                $authRoleUser = new AuthRoleUser();
                $authRoleUser->authRoleUserAdd($post['role'], $this->id);

                return $this->success(lang('Edit success'), url('index'));

            }else{
                return $this->error(lang('Edit failed'));
            }
        }

        return $this->error('请求错误');
    }

    //删除
    public function delete()
    {
        $delete = AdminBls::getOneAdmin(['admin_id' => $this->id]);

        if(!$this->request->isAjax() || empty($delete)){
            return $this->error('请求错误');
        }else if($delete->admin_id == 1){
            return $this->error('超级管理员不能删除');
        }

        //数据删除
        if($delete->delete()){

            //删除角色权限
            $authRoleUser = new AuthRoleUser();
            $authRoleUser->authRoleUserDelete($delete->admin_id);

            return $this->success('删除成功', url('index'));
        }else{
            return $this->error('删除失败');
        }

    }

    //登录权限
    public function privates()
    {
        $param      = intval($this->request->param('param'));
        $model   = AdminBls::getOneAdmin(['admin_id' => $this->id]);

        if(!$this->request->isAjax() && empty($delete)){
            return $this->error('请求错误');
        }else if($model->admin_id == 1){
            return $this->error('超级管理员不可操作');
        }

        $ratify = $model->save(['login_priv'=>$param]);
        if($ratify){
            return $this->success(lang('Success'), url($this->url));
        }else{
            return $this->error(lang('Failed'));
        }
    }

    //修改密码
    public function editPassword()
    {
        $model = AdminBls::getOneAdmin(['admin_id' => parent::$uid]);

        //密码修改数据处理
        if($this->request->isPost()){
            $post           = $this->request->post();
            $oldPassword    = Tool::get('helper')->getMd5($post['old_password']);
            $password       = Tool::get('helper')->getMd5($post['password']);


            //数据验证
            $result = $this->validate($post, 'app\common\bls\admin\validate\AdminValidate.edit_password');
            if(true !== $result){
                // 验证失败 输出错误信息
                return $this->error($result);
            }

            if($oldPassword != $model['admin_password']){
                return $this->error('原始密码错误');
            }else if($post['password'] != $post['repassword']){
                return $this->error('两次密码输入错误');
            }else if($oldPassword == $password){
                return $this->error('密码安全不够');
            }

            //修改数据
        if($model->save(['admin_password' => $password])){
                return $this->success(lang('Edit success'));
            }else{
                return $this->error(lang('Edit failed'));
            }

        }

        return $this->fetch('',[
            'info' => $model
        ]);
    }

    //重置管理员密码
    public function resetPassword()
    {
        if($this->request->isPost()){
            $post   = $this->request->post();

            //查找管理员
            $model   = AdminBls::getOneAdmin(['admin_name' => $post['name']]);
            if(empty($model)){
                return $this->error('没有这个管理员');
            }

            //匹配自身密码
            $admin  = AdminBls::getOneAdmin(['admin_id' => parent::$uid]);
            if($admin['admin_password'] != Tool::get('helper')->getMd5($post['password'])){
                return $this->error('自身密码输入错误');
            }

            //修改数据库
            $rand       = rand(11111111,99999999);
            $password   = Tool::get('helper')->getMd5($rand);
            if($model->save(['admin_password'=>$password])){
                return $this->success(lang('Edit success').' 管理员的密码是'.$rand);
            }else{
                return $this->error(lang('Edit failed'));
            }
        }
        return $this->fetch('');
    }

    /* //个人信息
    public function selfEdit()
    {
        return $this->fetch();
    }*/

    /**
     * 角色编译成html->option
     *
     * @param  mixed   $roleid
     * @return mixed
     */
    protected function role($roleid = [NUll])
    {
        $role = AuthRole::column('name','id');
        $html = '';
        foreach((array)$role as $k=>$v){
            $selected = in_array($k, $roleid)?'selected':'';
            $html   .= ' <option '.$selected.' value="'.$k.'">'.$v.'</option>';
        }

        return $html;
    }
}