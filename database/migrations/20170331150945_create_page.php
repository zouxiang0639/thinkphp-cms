<?php

use think\migration\Migrator;
use Phinx\Db\Adapter\MysqlAdapter;

class CreatePage extends Migrator
{
    /**
     * Migrate Up.
     */
    public function up()
    {
        $table = $this->table('page', ['id' => 'page_id', 'engine'=>'MyISAM', 'comment' => '页面表']);
        $table->addColumn('title', 'string', ['limit' => 100, 'comment' => '标题'])
            ->addColumn('template_type', 'integer', ['limit' => MysqlAdapter::INT_TINY, 'default' => 1, 'comment' => '模版模型'])
            ->addColumn('template_page', 'string', ['limit' => MysqlAdapter::INT_TINY, 'default' => 1, 'comment' => '默认模版'])
            ->addColumn('template_info', 'string', ['limit' => MysqlAdapter::INT_TINY, 'default' => 1, 'comment' => '详情模板'])
            ->addColumn('display', 'boolean', ['default' => 1, 'comment' => '显示: 0,删除, 1,所有人可见 2,不可见 3,管理员可见'])
            ->addColumn('list_row', 'integer', ['limit' => MysqlAdapter::INT_TINY, 'comment' => '每页行数'])
            ->addColumn('sort', 'integer', ['limit' => 10, 'comment' => '排序'])
            ->addColumn('comment', 'string', ['limit' => 255, 'comment' => '描述'])
            ->addColumn('keywords', 'string', ['limit' => 255, 'comment' => '关键字'])
            ->addColumn('description', 'string', ['limit' => 255, 'comment' => '描述'])
            ->addColumn('content', 'string', ['limit' => MysqlAdapter::TEXT_MEDIUM, 'comment' => '文章内容'])
            ->addColumn('photos', 'string', ['limit' => MysqlAdapter::TEXT_MEDIUM, 'comment' => '相册图集'])
            ->addColumn('picture', 'string', ['limit' => 100, 'comment' => '缩率图'])
            ->addColumn('path', 'string', ['limit' => 255, 'default' => '0', 'comment' => '导航路由'])
            ->addColumn('extend', 'string', ['limit' => MysqlAdapter::TEXT_MEDIUM, 'comment' => '扩展属性内容'])
            ->addColumn('links', 'string', ['limit' => 255, 'comment' => '外部链接'])
            ->addColumn('data_extended_id', 'integer', ['limit' => MysqlAdapter::INT_SMALL, 'comment' => '数据扩展ID'])
            ->addColumn('fields_extended_id', 'integer', ['limit' => MysqlAdapter::INT_SMALL, 'comment' => '字段扩展ID'])
            ->addTimestamps('create_time', 'update_time')
            ->save();
    }


    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('page');
    }
}
