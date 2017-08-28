<?php

use think\migration\Migrator;

class CreateAdmin extends Migrator
{
    /**
     * Migrate Up.
     */
    public function up()
    {
        $table = $this->table('admin', ['id' => 'admin_id', 'engine'=>'MyISAM', 'comment' => '管理员表']);
        $table->addColumn('admin_name', 'string', ['limit' => 100, 'comment' => '用户名'])
            ->addColumn('admin_password', 'char', ['limit' => 32, 'comment' => '用户密码'])
            ->addColumn('login_priv', 'boolean', ['default' => 1, 'comment' => '登录权限: 1,授权 2,关闭'])
            ->addColumn('last_login_ip', 'integer', ['limit' => 11, 'comment' => '最后登录IP'])
            ->addColumn('last_login_time', 'datetime', ['comment' => '最后登录时间'])
            ->addColumn('admin_email', 'string', ['limit' => 50, 'comment' => '管理员邮箱'])
            ->addColumn('comment', 'string', ['limit' => 255, 'comment' => '管理员介绍'])
            ->addColumn('sex', 'boolean', ['comment' => '性别:1,男 2,女'])
            ->addColumn('name', 'string', ['limit' => 50, 'comment' => '姓名'])
            ->addColumn('code', 'string', ['limit' => 50, 'comment' => '管理员编号'])
            ->addColumn('role', 'string', ['limit' => 100, 'comment' => '角色'])
            ->addIndex(['admin_name'], ['unique' => true])
            ->addTimestamps('create_time', 'update_time')
            ->save();

        //创建超级管理员
        $singleRow = [
            'admin_id'          => 1,
            'admin_name'        => 'admin',
            'admin_password'    => \app\common\tool\Tool::get('helper')->getMd5('admin')
        ];

        $table->insert($singleRow);
        $table->saveData();
    }


    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('admin');
    }
}
