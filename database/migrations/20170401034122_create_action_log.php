<?php

use think\migration\Migrator;
use Phinx\Db\Adapter\MysqlAdapter;

class CreateActionLog extends Migrator
{
    /**
     * Migrate Up.
     */
    public function up()
    {
        $table = $this->table('action_log', ['engine' => 'MyISAM', 'comment' => '行为日志表']);
        $table->addColumn('title', 'string', ['limit' => '255', 'comment' => '标题'])
            ->addColumn('role_id', 'integer', ['comment' => '执行用户ID'])
            ->addColumn('user_id', 'integer', ['comment' => '用户ID'])
            ->addColumn('action_ip', 'integer', ['limit' =>  MysqlAdapter::INT_BIG, 'comment' => '执行行为者ip'])
            ->addColumn('log', 'string', ['limit' =>  MysqlAdapter::TEXT_LONG, 'comment' => '日志备注'])
            ->addColumn('log_url', 'string', ['limit' => '100', 'comment' => '执行的URL'])
            ->addColumn('username', 'string', ['limit' => '100', 'comment' => '执行者名称'])
            ->addIndex(['user_id'])
            ->addIndex(['title'])
            ->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('action_log');
    }

}
