<?php
namespace app\appadmin\controller;

use app\common\model\AdminModel;
use app\common\tool\Tool;
use thinkcms\auth\model\AuthRole;
use thinkcms\auth\model\AuthRoleUser;

class Admin extends BasicController
{
    protected $validate;
    protected $url  = 'admin/index';
    protected $id   = 0;

    public function __construct()
    {

        parent::__construct();
        $this->id       = !empty($this->request->param('id'))?intval($this->request->param('id')):$this->id;
        $this->validate = [
            ['admin_name|用户名', 'require|unique:admin,admin_name,'.$this->id.',admin_id'],
            ['admin_email|邮箱', 'email'],
            ['admin_password|密码', 'require'],
            ['role|角色', 'require'],
        ];

        $nav = [
            '管理员列表' =>['url'=>'admin/index'],
            '管理员增加' =>['url'=>'admin/add'],
            '管理员修改' =>['url'=>['admin/edit', ['id' => $this->id]], 'style'=>"display: none;"],
        ];
        $this->assign('navTabs',  parent::navTabs($nav));
    }

    //首页
    public function index()
    {
        $list   = AdminModel::paginate(20);

        return $this->fetch('',[
            'list'  => $list,
            'page'  => $list->render()
        ]);
    }

    //增加
    public function add()
    {
        //add_post 数据处理
        if($this->request->isPost()){
            $post = $this->request->post();

            //数据验证
            $result = $this->validate($post,$this->validate);
            if (true !== $result) {
                return $this->error($result);
            }

            //写入数据库
            $post['admin_password'] = Tool::get('helper')->getMd5($post['admin_password']);
            $add = AdminModel::create($post);
            if($add){

                //加入角色
                $authRoleUser = new AuthRoleUser();
                $authRoleUser->authRoleUserAdd($post['role'], $add['admin_id']);

                return $this->success(lang('Add success'), url($this->url));
            }else{
                return $this->error(lang('Add failed'));
            }
        }

        //页面渲染
        $info['role_html'] = self::role();

        return $this->fetch('',[
            'info' => $info
        ]);
    }

    //编辑
    public function edit()
    {
        $info = AdminModel::get($this->id);
        if(empty($info)){
          return abort(404, lang('404 not found'));
        }
        $info['role_html'] = self::role($info['role']);

        return $this->fetch('',[
            'info' => $info
        ]);
    }

    //修改数据
    public function editPost()
    {

        if(!empty($this->id) && $this->request->isPost()){
            $post   = $this->request->post();

            //数据验证
            unset($this->validate[2], $this->validate[0]); //销毁用户名  密码验证
            $result = $this->validate($post, $this->validate);
            if (true !== $result) {
                return $this->error($result);
            }

            //修改数据
            $sdit   = AdminModel::get($this->id);
            if($sdit->save($post)){

                //加入角色
                $authRoleUser = new AuthRoleUser();
                $authRoleUser->authRoleUserAdd($post['role'], $this->id);

                return $this->success(lang('Edit success'), url($this->url));

            }else{
                return $this->error(lang('Edit failed'));
            }
        }

        return abort(404, lang('404 not found'));
    }

    //删除
    public function delete()
    {
        $delete = AdminModel::get($this->id);

        if(!$this->request->isAjax() || empty($delete)){
            return abort(404, lang('404 not found'));
        }else if($delete->admin_id == 1){
            return $this->error('超级管理员不能删除');
        }

        //数据删除
        if($delete->delete()){

            //删除角色权限
            $authRoleUser = new AuthRoleUser();
            $authRoleUser->authRoleUserDelete($delete->admin_id);

            return $this->success('删除成功',url($this->url));
        }else{
            return $this->error('删除失败');
        }

    }

    //登录权限
    public function privates()
    {
        $param      = intval($this->request->param('param'));
        $privates   = AdminModel::get($this->id);

        if(!$this->request->isAjax() && empty($delete)){
            return abort(404, lang('404 not found'));
        }else if($privates->admin_id == 1){
            return $this->error('超级管理员不可操作');
        }

        $ratify = $privates->save(['login_priv'=>$param]);
        if($ratify){
            return $this->success(lang('Success'), url($this->url));
        }else{
            return $this->error(lang('Failed'));
        }
    }

    //修改密码
    public function editPassword()
    {
        $info = AdminModel::get(parent::$uid);

        //密码修改数据处理
        if($this->request->isPost()){
            $post           = $this->request->post();
            $oldPassword    = Tool::get('helper')->getMd5($post['old_password']);
            $password       = Tool::get('helper')->getMd5($post['password']);

            //数据验证
            $validate = [
                ['password|新密码', 'require']
            ];
            $result = $this->validate($post,$validate);
            if (true !== $result) {
                return $this->error($result);
            }
            if($oldPassword != $info['admin_password']){
                return $this->error('原始密码错误');
            }else if($post['password'] != $post['repassword']){
                return $this->error('两次密码输入错误');
            }else if($oldPassword == $password){
                return $this->error('密码安全不够');
            }

            //修改数据
            if($info->save(['admin_password' => $password])){
                return $this->success(lang('Edit success'));
            }else{
                return $this->error(lang('Edit failed'));
            }

        }

        return $this->fetch('',[
            'info' => $info
        ]);
    }

    //重置管理员密码
    public function resetPassword()
    {
        if($this->request->isPost()){
            $post   = $this->request->post();

            //查找管理员
            $user   = AdminModel::get(['admin_name' => $post['name']]);
            if(empty($user)){
                return $this->error('没有这个管理员');
            }

            //匹配自身密码
            $admin  = AdminModel::get(parent::$uid);
            if($admin['admin_password'] != Tool::get('helper')->getMd5($post['password'])){
                return $this->error('自身密码输入错误');
            }

            //修改数据库
            $rand       = rand(11111111,99999999);
            $password   = Tool::get('helper')->getMd5($rand);
            if($user->save(['admin_password'=>$password])){
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