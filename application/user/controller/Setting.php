<?php
namespace app\user\controller;

use app\common\bls\user\UserBls;
use app\common\library\email\Email;
use app\common\tool\Tool;
use think\Session;

class Setting extends BasicController
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

    /**
     * 激活邮箱
     * @return mixed
     * @throws \phpmailerException
     */
    public function activate(){
        $model = $this->request->getUser();
        $model->info = '邮箱已绑定';

        if($model->is_email == 0) {
            $model->token =  $this->request->token();

            $mail = new Email();

            $mail->addAddress($model->email);     // Add a recipient
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = '绑定邮箱';

            $url = url('bindMailbox', ['token' => $model->token], true, true);
            $html ="尊敬的用户：<br>您好！<br>";
            $html.='<br>为了保障您帐号的安全性，请在 10分钟内完成绑定邮箱。';
            $html.='<a href="'.$url.'">点击绑定邮箱</a>';
            $mail->Body    = $html;

            if(!$mail->send()) {
                $model->info = '邮件发送失败,请确定你的邮箱';
            } else {
                $model->save();
                //记录过期时间
                Session::set('bind_expiration_date', time());
                $model->info =  '激活链接已发送';
            }
        }

        return $this->fetch('', [
            'info' => $model
        ]);
    }

    /**
     * 绑定邮箱
     * @return mixed
     */
    public function bindMailbox()
    {
        $model = $this->request->getUser();

        $time = Session::get('bind_expiration_date') + 600;

        if($model->is_email) {
            $model->info = '邮箱已绑定';
        } else if($model->token != input('token')) {
            $model->info = '令牌错误';
        } else if($time < time()) {
            $model->info = '时间过期';
        } else {
            $model->info = '邮箱已绑定';
            $model->is_email = 1;
            $model->save();
        }

        return $this->fetch('activate', [
            'info' => $model
        ]);
    }

}