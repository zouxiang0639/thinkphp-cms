<?php

use think\migration\Seeder;
use app\common\consts\common\CommonFormInputConst;

class Configure extends Seeder
{
    //菜单数据
    public $data = [];
    /**
     * Run Method.
     */
    public function run()
    {
        $array = [
            $this->basicSettings(),
            $this->emailSettings()
        ];

        foreach($array as $v){
            $this->recursion($v);
        }

        $table = $this->table('configure');
        $table->insert($this->data);
        $table->saveData();
    }

    /**
     * 递归
     */
    public function recursion($data)
    {
        foreach($data as $v){
            $this->factory($v);
        }
    }

    /**
     * 工厂
     */
    public function factory($date)
    {
        $add[] = [
            'title'             => array_get($date, 'title', ''),
            'configure_name'    => array_get($date, 'configure_name', ''),
            'configure_value'   => array_get($date, 'configure_value', ''),
            'input_type'         => array_get($date, 'form_type', ''),
            'type'            => array_get($date, 'groups', 1),
            'comment'           => array_get($date, 'comment', ''),
        ];

        $this->data = array_merge($this->data, $add);
    }

    /**
     * 基本配置
     */
    public function basicSettings($groups = 1)
    {
        $array = [
            [
                'title'             => '网站标题',
                'configure_name'    => 'title',
                'form_type'         => CommonFormInputConst::TEXT,
                'groups'            => $groups,
                'comment'           => '（网站标题前台显示标题）',
            ],[
                'title'             => '网站描述',
                'configure_name'    => 'description',
                'form_type'         => CommonFormInputConst::TEXTAREA,
                'groups'            => $groups,
                'comment'           => '（网站搜索引擎描述）',
            ],[
                'title'             => '网站关键字',
                'configure_name'    => 'keywords',
                'form_type'         => CommonFormInputConst::TEXTAREA,
                'groups'            => $groups,
                'comment'           => '（网站搜索引擎关键字） 多个用 ( , )隔开',
            ],[
                'title'             => '网站备案号',
                'configure_name'    => 'icp',
                'form_type'         => CommonFormInputConst::TEXT,
                'groups'            => $groups,
                'comment'           => '（设置在网站底部显示的备案号，如“沪ICP备12007941号-2）',
            ],[
                'title'             => '默认图片',
                'configure_name'    => 'default_picture',
                'form_type'         => CommonFormInputConst::TEXT,
                'groups'            => $groups,
                'comment'           => '网站默认图片',
            ]
        ];

        return $array;
    }

    /**
     * 邮箱配置
     */
    public function emailSettings($groups = 2)
    {
        $array = [
            [
                'title'             => 'SMTP服务器',
                'configure_name'    => 'smtp_host',
                'form_type'         => CommonFormInputConst::TEXT,
                'groups'            => $groups,
            ],[
                'title'             => 'SMTP用户名',
                'configure_name'    => 'smtp_user',
                'form_type'         => CommonFormInputConst::TEXT,
                'groups'            => $groups,
            ],[
                'title'             => 'SMTP密码',
                'configure_name'    => 'smtp_pass',
                'form_type'         => CommonFormInputConst::TEXT,
                'groups'            => $groups,
            ],[
                'title'             => 'SMTP端口',
                'configure_name'    => 'smtp_port',
                'form_type'         => CommonFormInputConst::TEXT,
                'groups'            => $groups,
            ],[
                'title'             => '发件邮箱',
                'configure_name'    => 'send_email',
                'form_type'         => CommonFormInputConst::TEXT,
                'groups'            => $groups,
            ],[
                'title'             => '发件名称',
                'configure_name'    => 'sender_name',
                'form_type'         => CommonFormInputConst::TEXT,
                'groups'            => $groups,
            ]
        ];
        return $array;
    }
}