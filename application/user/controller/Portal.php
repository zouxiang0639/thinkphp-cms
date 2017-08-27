<?php
namespace app\user\controller;

use app\common\base\BaseController;
use app\common\bls\user\UserBls;
use app\common\library\email\Email;
use app\common\tool\Tool;
use think\Session;

class Portal extends BaseController
{

    /**
     * 登入会员
     */
    public function login()
    {
        if($this->request->isPost()) {
            $post = $this->request->post();

            //数据验证
            $result = $this->validate($post, 'app\common\bls\user\validate\UserValidate.login');
            if(true !== $result){
                // 验证失败 输出错误信息
                return $this->error($result, '', $this->request->token());
            }

            $result = UserBls::userLogin($post['email'], $post['password']);
            if($result === true) {
                return $this->success('登入成功', url('index/index'));
            } else {
                return $this->error($result, '', $this->request->token());
            }

        }

        return $this->fetch('');
    }

    /**
     * 注册会员
     */
    public function register()
    {

        if($this->request->isPost()) {
            $post = $this->request->post();

            //数据验证
            $result = $this->validate($post, 'app\common\bls\user\validate\UserValidate.register');
            if(true !== $result){
                // 验证失败 输出错误信息
                return $this->error($result, '', $this->request->token());
            }

            if($user = UserBls::createUser($post)) {

                //写人session
                UserBls::setSession($user->user_id, $user->username);

                return $this->success('注册成功', url('index/index'));
            } else {
                return $this->error('注册失败');
            }
        }

        return $this->fetch('');
    }

    /**
     * 退出会员
     */
    public function logout()
    {
        UserBls::logout();
        return $this->redirect('login');
    }

    /**
     * 找回密码
     */
    public function forget()
    {

        if($this->request->isPost()) {

            $post = $this->request->post();
            //数据验证
            $result = $this->validate($post, 'app\common\bls\user\validate\UserValidate.forget');
            if(true !== $result){
                // 验证失败 输出错误信息
                return $this->error($result, '', $this->request->token());
            }

            $model = UserBls::getOneUser(['email' => $post['email']]);
            if(empty($model)) {
                return $this->error('不存在该用户', '', $this->request->token());
            }
            $model->token = $this->request->token();
            $model->save();

            $mail = new Email();

            $mail->addAddress('542506511@qq.com', 'Joe User');     // Add a recipient
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = '找回密码';

            $url = url('setPassword', ['token'=>$model->token, 'email'=> $post['email']], true, true);
            $html ="亲爱的用户：<br>您好！<br>";
            $html.='<br>为了保障您帐号的安全性，请在 10分钟内完成修改密码。';
            $html.='<a href="'.$url.'">点击跳转修改密码</a>';
            $mail->Body    = $html;

            if(!$mail->send()) {
                return $this->error('邮箱发送失败', '', $this->request->token());
                //echo 'Mailer Error: ' . $mail->ErrorInfo;
            } else {

                //记录过期时间
                Session::set('expiration_date', time());

                return $this->success('邮件发送成功');
            }
        }

        return $this->fetch('');
    }

    /**
     * 设置密码
     */
    public function setPassword()
    {
        $token = $this->request->get('token');
        $email = $this->request->get('email');

        $modle = UserBls::getOneUser(['email' => $email]);
        if(empty($modle)) {
            return $this->error('不存在该用户', url('forget'));
        }

        $expirationDate = Session::get('expiration_date') + 600;
        if(!empty($expirationDate) && $expirationDate < time()) {
            return $this->error('时间已过期', url('forget'));
        } else if($token !== $modle->token) {
            return $this->error('令牌错误', url('forget'));
        }

        if($this->request->isPost()) {

            $post = $this->request->post();
            //数据验证
            $result = $this->validate($post, 'app\common\bls\user\validate\UserValidate.setPassword');
            if(true !== $result){
                // 验证失败 输出错误信息
                return $this->error($result, '', $this->request->token());
            }
            $modle->password = Tool::get('helper')->getMd5($post['password']);
            if($modle->save()) {
                Session::delete('expiration_date');
                return $this->success('修改成功', url('login'));
            } else {
                return $this->error('安全不够请重新输入密码');
            }
        }

        return $this->fetch('');

    }
}