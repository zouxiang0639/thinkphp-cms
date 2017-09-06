<?php

use think\migration\Migrator;
use Phinx\Db\Adapter\MysqlAdapter;

class CreateLabel extends Migrator
{

    /**
     * Migrate Up.
     */
    public function up()
    {
        $table = $this->table('label', ['id' => 'label_id', 'engine'=>'InnoDB', 'comment' => '用户表']);
        $table->addColumn('title', 'string', ['limit' => 100, 'comment' => '标题'])
            ->addColumn('icon', 'string', ['limit' => 100, 'comment' => '图标'])
            ->addColumn('alphabet', 'string', ['limit' => 20, 'comment' => '字母'])
            ->addColumn('type', 'boolean', ['default' => 1, 'comment' => '类型 详细LabelTypeConst'])
            ->addTimestamps('create_time', 'update_time')
            ->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('label');
    }
}
