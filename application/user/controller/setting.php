<?php
namespace app\user\controller;

use app\common\bls\user\UserBls;
use app\common\tool\Tool;

class setting extends BasicController
{
    /**
     * 基本设置
     */
    public function profile()
    {
        $model = $this->request->getUser()->formatUsers()->offsetGet(0);

        return $this->fetch('', [
            'info' => $model
        ]);
    }

    /**
     * 我的资料修改
     */
    public function basicEdit()
    {
        if($this->request->isPost()) {
            $post = $this->request->post();
            $model = $this->request->getUser();

            //数据验证
            $result = $this->validate($post, 'app\common\bls\user\validate\UserValidate.basicEdit');
            if(true !== $result){
                // 验证失败 输出错误信息
                return $this->error($result, '', $this->request->token());
            }

            if(UserBls::editUser($model, $post)) {
                return $this->success('修改成功');
            } else {
                return $this->error('修改失败', '', $this->request->token());
            }
        }

        return $this->error('请求错误');
    }

    /**
     * 修改密码
     */
    public function setPassword()
    {
        if($this->request->isPost()) {
            $post = $this->request->post();
            $model = $this->request->getUser();

            //数据验证
            $result = $this->validate($post, 'app\common\bls\user\validate\UserValidate.setPassword');
            if(true !== $result){
                // 验证失败 输出错误信息
                return $this->error($result, '', $this->request->token());
            }

            $password = Tool::get('helper')->getMd5($post['old_password']);
            if($model->password !== $password) {
                return $this->error('原始密码不对', '', $this->request->token());
            }

            $model->password =  Tool::get('helper')->getMd5($post['password']);
            if($model->save()) {
                return $this->success('修改成功');
            } else {
                return $this->error('修改失败', '', $this->request->token());
            }

        }

        return $this->error('请求错误');
    }

    /**
     * 上传头像
     */
    public function uploadAvatar()
    {
        if($this->request->isPost()) {
            $model = $this->request->getUser();

            $file = request()->file('file');

            // 移动到框架应用根目录/public/uploads/avatar 目录下
            $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads'. DS .'avatar');

            if($info){
                $path = DS . strstr($info->getPathname(), 'uploads');
            }else{
                // 上传失败获取错误信息
                return $this->error($file->getError(), $this->request->token());
            }

            $model->avatar = $path;

            if($model->save()) {
                return $this->success('上传成功', '', $path);
            } else {
                return $this->error('上传失败', '', $this->request->token());
            }

        }


    }

    public function activate(){
        $model = $this->request->getUser();
        return $this->fetch('', [
            'info' => $model
        ]);
    }

}