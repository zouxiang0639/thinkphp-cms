<?php
namespace app\manage\controller\user;

use app\common\bls\user\UserBls;
use app\common\consts\user\UserStatusConst;
use app\common\library\email\Email;
use app\common\tool\Tool;
use app\manage\controller\BasicController;
use app\common\bls\user\traits\UserTrait;

class Index extends BasicController
{
    use UserTrait;

    public $id;

    public function __construct()
    {
        parent::__construct();
        $this->id       = !empty($this->request->param('id')) ? intval($this->request->param('id')) : $this->id;
        $nav = [
            '用户列表' => ['url' => 'index'],
            '用户详细' => ['url' => ['show', ['id' => $this->id]], 'style' => "display: none;"],
        ];
        $this->assign('navTabs',  parent::navTabs($nav));
    }

    /**
     * 会员列表
     */
    public function index()
    {
        $where = [];
        if(!empty(input('email'))) {
            $where['email'] = input('email');
        }
        $model = UserBls::getUserList($where);
        $this->formatUser($model->getCollection());
        return $this->fetch('/user/user/index' ,[
            'list' => $model
        ]);
    }


    /**
     * 修改状态
     */
    public function status()
    {
        if($this->request->isPost()) {

            $get = $this->request->get();

            $where = [];
            if($get['status'] == UserStatusConst::ABLE) {
                $where['status'] = UserStatusConst::UNABLE;
            } else {
                $where['status'] = UserStatusConst::ABLE;
            }
            $where['user_id'] = $get['id'];

            $model = UserBls::getOneUser($where);
            $model->status = $get['status'];

            if($model->save()) {
                return $this->success('操作成功', url('index'));
            }else{
                return $this->error('操作失败');
            }
        }
    }

    /**
     * 用户详细
     */
    public function show()
    {
        $modle = UserBls::getOneUser(['user_id'=>input('id')]);
        return $this->fetch('/user/user/show', [
            'info' => $modle
        ]);
    }

    /**
     * 重置密码
     */
    public function setPassword()
    {
        if($this->request->isPost()) {
            $model = UserBls::getOneUser(['user_id'=>input('id')]);
            if(empty($model)) {
                return $this->error('参数错误');
            }
            $model->password = Tool::get('helper')->getMd5(123456);
            if($model->save()){
                $mail = new Email();

                $mail->addAddress($model['email']);
                $mail->isHTML(true);
                $mail->Subject = '后台重置密码';

                $html ="尊敬的用户：<br>您好！<br>";
                $html.='<br>后台管理员给你重置了密码, 您的密码是123456, 请尽快登入会员中心后修改密码';
                $mail->Body    = $html;
                $mail->send();

                return $this->success('操作成功', url('index'));
            } else {
                return $this->error('操作失败');
            }
        }
    }
}