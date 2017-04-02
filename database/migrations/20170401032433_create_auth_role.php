<?php

use think\migration\Migrator;
use Phinx\Db\Adapter\MysqlAdapter;

class CreateAuthRole extends Migrator
{
    /**
     * Migrate Up.
     */
    public function up()
    {
        $table = $this->table('auth_role', ['engine'=>'InnoDB', 'comment' => '角色表']);
        $table->addColumn('name', 'string', ['limit' => 50, 'comment' => '角色名称'])
            ->addColumn('status', 'boolean', ['signed'=>false, 'comment' => '状态: 1,授权 2,关闭'])
            ->addColumn('remark', 'string', ['limit' => 255, 'comment' => '备注'])
            ->addColumn('listorder', 'integer', ['limit' => MysqlAdapter::INT_TINY, 'comment' => '排序字段'])
            ->addTimestamps('create_time', 'update_time')
            ->addIndex(['status'])
            ->save();

        //创建超级角色
        $singleRow = [
            'name'          => '超级管理员',
            'status'        => 1,
            'remark'        => '超级管理员角色可以操作系统所有操作'
        ];
        $table->insert($singleRow);
        $table->saveData();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('auth_role');
    }
}
