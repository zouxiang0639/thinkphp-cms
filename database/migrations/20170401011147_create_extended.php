<?php

use think\migration\Migrator;
use Phinx\Db\Adapter\MysqlAdapter;

class CreateExtended extends Migrator
{
    /**
     * Migrate Up.
     */
    public function up()
    {
        $table = $this->table('extended', ['id' => 'extended_id', 'engine'=>'MyISAM', 'comment' => '字段扩展表']);
        $table->addColumn('input_type', 'integer', ['limit' => MysqlAdapter::INT_TINY, 'default' => 1, 'comment' => '表单类型 CommonFormInputConst'])
            ->addColumn('input_value', 'string', ['limit' => 255, 'comment' => '表单默认值'])
            ->addColumn('comment', 'string', ['limit' => 255, 'comment' => '描述'])
            ->addColumn('title', 'string', ['limit' => 255, 'comment' => '标题'])
            ->addColumn('type', 'boolean', ['default' => 1, 'comment' => '类型'])
            ->addColumn('parent_id', 'integer', ['limit' => MysqlAdapter::INT_SMALL, 'comment' => '父级ID'])
            ->addColumn('name', 'string', ['limit' => 100, 'comment' => '字段或数据库名称'])
            ->addColumn('mysql_fields_type', 'integer', ['limit' => MysqlAdapter::INT_SMALL, 'comment' => '数据库字段类型'])
            ->addColumn('mysql_fields_length', 'string', ['limit' => 50, 'comment' => '数据库字段长度'])
            ->addColumn('mysql_fields_default', 'string', ['limit' => 50, 'comment' => '数据库字段默认值'])
            ->addColumn('sort', 'integer', ['limit' => MysqlAdapter::INT_SMALL, 'default' => 0, 'comment' => '排序'])
            ->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('extended');
    }
}
