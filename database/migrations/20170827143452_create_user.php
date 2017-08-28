<?php

use think\migration\Migrator;
use Phinx\Db\Adapter\MysqlAdapter;

class CreateUser extends Migrator
{

    /**
     * Migrate Up.
     */
    public function up()
    {
        $table = $this->table('user', ['id' => 'user_id', 'engine'=>'InnoDB', 'comment' => '用户表']);
        $table->addColumn('username', 'string', ['limit' => 100, 'comment' => '用户名'])
            ->addColumn('nickname', 'string', ['limit' => 255, 'comment' => '昵称'])
            ->addColumn('password', 'char', ['limit' => 32, 'comment' => '用户密码'])
            ->addColumn('email', 'string', ['limit' => 100, 'comment' => '邮箱'])
            ->addColumn('is_email', 'boolean', ['default' => 0, 'comment' => '邮箱是否绑定'])
            ->addColumn('sex', 'boolean', ['default' => 0, 'comment' => '性别 详细CommonSexConst'])
            ->addColumn('comment', 'string', ['limit' => 255, 'comment' => '简介'])
            ->addColumn('avatar', 'string', ['limit' => 100, 'comment' => '头像'])
            ->addColumn('token', 'string', ['limit' => 100, 'comment' => '令牌'])
            ->addColumn('birthday', 'datetime', ['comment' => '生日'])
            ->addColumn('status', 'boolean', ['default' => 1, 'comment' => '状态 详细UserStatusConst'])
            ->addIndex(['user_id'])
            ->addTimestamps('create_time', 'update_time')
            ->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('user');
    }
}
