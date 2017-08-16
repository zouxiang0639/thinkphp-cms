<?php

use think\migration\Migrator;
use Phinx\Db\Adapter\MysqlAdapter;

class CreateGoods extends Migrator
{

    /**
     * Migrate Up.
     */
    public function up()
    {
        $table = $this->table('goods', ['id' => 'goods_id', 'engine'=>'InnoDB', 'comment' => '信息表']);
        $table->addColumn('title', 'string', ['limit' => 100, 'comment' => '标题'])
            ->addColumn('page_id', 'integer', ['limit' => MysqlAdapter::INT_SMALL, 'comment' => '分类导航ID'])
            ->addColumn('display', 'boolean', ['default' => 1, 'comment' => '显示: 0,删除, 1,所有人可见 2,不可见 3,管理员可见'])
            ->addColumn('sort', 'integer', ['limit' => 10, 'comment' => '排序'])
            ->addColumn('comment', 'string', ['limit' => 255, 'comment' => '描述'])
            ->addColumn('keywords', 'string', ['limit' => 255, 'comment' => '关键字'])
            ->addColumn('description', 'string', ['limit' => 255, 'comment' => '描述'])
            ->addColumn('content', 'string', ['limit' => MysqlAdapter::TEXT_MEDIUM, 'comment' => '文章内容'])
            ->addColumn('photos', 'string', ['limit' => MysqlAdapter::TEXT_MEDIUM, 'comment' => '相册图集'])
            ->addColumn('picture', 'string', ['limit' => 100, 'comment' => '缩率图'])
            ->addColumn('extend', 'string', ['limit' => MysqlAdapter::TEXT_MEDIUM, 'comment' => '扩展属性内容'])
            ->addIndex(['page_id'])
            ->addIndex(['display'])
            ->addIndex(['title'])
            ->addTimestamps('create_time', 'update_time')
            ->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('goods');
    }
}
