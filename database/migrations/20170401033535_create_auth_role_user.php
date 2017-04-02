<?php

use think\migration\Migrator;


class CreateAuthRoleUser extends Migrator
{
    /**
     * Migrate Up.
     */
    public function up()
    {
        $table = $this->table('auth_role_user', ['id' => false, 'engine' => 'InnoDB', 'comment' => '用户角色中间表']);
        $table->addColumn('role_id', 'integer', ['comment' => '角色ID'])
            ->addColumn('user_id', 'boolean', ['comment' => '用户ID'])
            ->addIndex(['role_id'])
            ->addIndex(['user_id'])
            ->save();
    }
    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('auth_role_user');
    }
}
