<?php

use think\migration\Migrator;
use Phinx\Db\Adapter\MysqlAdapter;

class CreateForms extends Migrator
{
    /**
     * Migrate Up.
     */
    public function up()
    {
        $table = $this->table('forms', ['id' => 'forms_id', 'engine'=>'InnoDB', 'comment' => '表单信息']);
        $table->addColumn('extended_id', 'integer', ['limit' => 11, 'comment' => '扩展ID'])
            ->addColumn('user_id', 'integer', ['limit' => 11, 'comment' => '用户ID'])
            ->addColumn('extend', 'string', ['limit' => MysqlAdapter::TEXT_MEDIUM, 'comment' => '表单信息内容'])
            ->addTimestamps('create_time', 'update_time')
            ->addIndex(['extended_id'])
            ->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('forms');
    }
}
