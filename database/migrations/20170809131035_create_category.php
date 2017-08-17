<?php

use think\migration\Migrator;
use Phinx\Db\Adapter\MysqlAdapter;

class CreateCategory extends Migrator
{

    /**
     * Migrate Up.
     */
    public function up()
    {
        $table = $this->table('category', ['id' => 'category_id', 'comment' => '导航表']);
        $table->addColumn('title', 'string', ['limit' => 100, 'comment' => '标题'])
            ->addColumn('parent_id', 'integer', ['limit' => MysqlAdapter::INT_SMALL, 'comment' => '父级ID', 'signed'=>false])
            ->addColumn('page_id', 'integer', ['limit' => MysqlAdapter::INT_SMALL, 'comment' => '', 'signed'=>false])
            ->addColumn('group', 'integer', ['limit' => MysqlAdapter::INT_TINY, 'default' => 0, 'comment' => '导航分类 NavigateGroupConst'])
            ->addColumn('bind_page', 'integer', ['limit' => MysqlAdapter::INT_TINY, 'default' => 0, 'comment' => '绑定页面类型 1:本地连接 2外部连接  NavigateBindPageConst'])
            ->addColumn('path', 'string', ['limit' => 150, 'comment' => '路由'])
            ->addColumn('status', 'boolean', ['default' => 1, 'comment' => '状态:  1,所有人可见 2,不可见 3,管理员可见'])
            ->addColumn('links', 'string', ['limit' => 200, 'comment' => '外部链接'])
            ->addColumn('sort', 'integer', ['limit' => 10, 'comment' => '排序'])
            ->addTimestamps('create_time', 'update_time')
            ->addIndex(['parent_id'])
            ->save();
    }


    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('category');
    }
}
