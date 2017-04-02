<?php

use think\migration\Migrator;
use Phinx\Db\Adapter\MysqlAdapter;

class CreateTemplate extends Migrator
{
    /**
     * Migrate Up.
     */
    public function up()
    {
        $table = $this->table('template', ['id' => 'template_id', 'engine'=>'MyISAM', 'comment' => '模版表']);
        $table->addColumn('title', 'string', ['limit' => 100, 'comment' => '标题'])
            ->addColumn('group', 'integer', ['default' => 1, 'limit' => MysqlAdapter::INT_TINY, 'comment' => '模版分组模型: 1,单页模型 2,信息模型'])
            ->addColumn('type', 'integer', ['default' => 1, 'limit' => MysqlAdapter::INT_TINY, 'comment' => '模版类型: 1,分类页面 2,信息内页 3,通用页面'])
            ->addColumn('template_file', 'string', ['limit' => 100, 'comment' => '模版文件'])
            ->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('template');
    }
}
