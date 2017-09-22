<?php

use think\migration\Migrator;
use Phinx\Db\Adapter\MysqlAdapter;

class CreateIntegralLog extends Migrator
{
    /**
     * Migrate Up.
     */
    public function up()
    {
        $table = $this->table('integral_log', ['id' => 'integral_log_id', 'engine'=>'InnoDB', 'comment' => ' 积分日志']);
        $table->addColumn('integral_rule_id', 'integer', ['limit' => 11, 'comment' => '积分规则ID'])
            ->addColumn('user_id', 'integer', ['limit' => 11, 'comment' => '用户ID'])
            ->addColumn('integral', 'integer', ['limit' => 11, 'comment' => '积分'])
            ->addColumn('title', 'string', ['limit' => 255, 'comment' => '标题'])
            ->addColumn('type', 'boolean', ['default' => 1,'comment' => '类型 IntegralTypeConst'])
            ->addTimestamps('create_time', 'update_time')
            ->addIndex(['integral_log_id'])
            ->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('integral_log');
    }
}
