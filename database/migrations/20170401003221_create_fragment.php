<?php

use think\migration\Migrator;
use Phinx\Db\Adapter\MysqlAdapter;

class CreateFragment extends Migrator
{
    /**
     * Migrate Up.
     */
    public function up()
    {
        $table = $this->table('fragment', ['id' => 'fragment_id', 'engine'=>'MyISAM', 'comment' => '信息碎片表']);
        $table->addColumn('title', 'string', ['limit' => 100, 'comment' => '标题'])
            ->addColumn('links', 'string', ['limit' => 150, 'comment' => '链接'])
            ->addColumn('picture', 'string', ['limit' => 150, 'comment' => '缩率图'])
            ->addColumn('comment', 'string', ['limit' => 255, 'comment' => '简介'])
            ->addColumn('content', 'string', ['limit' => MysqlAdapter::TEXT_MEDIUM, 'comment' => '模版类型'])
            ->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('fragment');
    }
}
