<?php

use think\migration\Migrator;

class CreateAuthAccess extends Migrator
{
    /**
     * Migrate Up.
     */
    public function up()
    {
        $table = $this->table('auth_access', ['id' => false, 'engine'=>'InnoDB', 'comment' => '权限授权表']);
        $table->addColumn('role_id', 'integer', ['signed'=>false, 'comment' => '角色ID'])
            ->addColumn('menu_id', 'integer', ['signed'=>false, 'comment' => '后台菜单ID'])
            ->addColumn('rule_name', 'string', ['limit' => 255, 'comment' => '规则唯一英文标识,全小写'])
            ->addColumn('type', 'string', ['limit' => 30, 'comment' => '权限规则分类，请加应用前缀,如admin_'])
            ->addIndex(['role_id'])
            ->addIndex(['rule_name'])
            ->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('auth_access');
    }
}
