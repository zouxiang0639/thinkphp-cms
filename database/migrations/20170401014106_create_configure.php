<?php

use think\migration\Migrator;
use Phinx\Db\Adapter\MysqlAdapter;

class CreateConfigure extends Migrator
{
    /**
     * Migrate Up.
     */
    public function up()
    {
        $table = $this->table('configure', ['id' => 'configure_id', 'engine'=>'MyISAM', 'comment' => '配置管理表']);
        $table->addColumn('title', 'string', ['limit' => 100, 'comment' => '标题'])
            ->addColumn('configure_name', 'string', ['limit' => 100, 'comment' => '配置名称'])
            ->addColumn('configure_value', 'string', ['limit' => 255, 'comment' => '配置默认值'])
            ->addColumn('input_type', 'string', ['limit' => 100, 'default' => 'text', 'comment' => '表单类型'])
            ->addColumn('type', 'integer', ['limit' => MysqlAdapter::INT_TINY, 'default' => 1, 'comment' => '类型 '])
            ->addColumn('comment', 'string', ['limit' => 255, 'comment' => '描述'])
            ->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('configure');
    }
}
