<?php

use think\migration\Migrator;
use Phinx\Db\Adapter\MysqlAdapter;

class CreateIntegralRule extends Migrator
{
    /**
     * Migrate Up.
     */
    public function up()
    {
        $table = $this->table('integral_rule', ['id' => 'integral_rule_id', 'engine'=>'InnoDB', 'comment' => ' 积分日志']);
        $table->addColumn('integral', 'integer', ['limit' => 11, 'comment' => '积分'])
            ->addColumn('title', 'string', ['limit' => 255, 'comment' => '标题'])
            ->addColumn('rule_method', 'string', ['limit' => 255, 'comment' => '规则方法 IntegralRuleMethodConst'])
            ->addColumn('status', 'integer', ['default' => 1, 'comment' => '状态  详细：CommonSwitchConst'])
            ->addTimestamps('create_time', 'update_time')
            ->addIndex(['integral_rule_id'])
            ->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('integral_rule');
    }
}

