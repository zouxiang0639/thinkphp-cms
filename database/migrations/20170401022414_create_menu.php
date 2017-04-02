<?php

use think\migration\Migrator;
use Phinx\Db\Adapter\MysqlAdapter;

class CreateMenu extends Migrator
{
    /**
     * Migrate Up.
     */
    public function up()
    {
        $table = $this->table('menu', ['id', 'engine'=>'MyISAM', 'comment' => '幻灯片表']);
        $table->addColumn('name', 'string', ['limit' => 100, 'comment' => '标题'])
            ->addColumn('parent_id', 'integer', ['limit' => MysqlAdapter::INT_SMALL, 'comment' => '父级ID', 'signed'=>false])
            ->addColumn('app', 'char', ['limit' => 20, 'comment' => '应用名称app'])
            ->addColumn('model', 'char', ['limit' => 20, 'comment' => '控制器'])
            ->addColumn('action', 'char', ['limit' => 20, 'comment' => '操作名称'])
            ->addColumn('url_param', 'char', ['limit' => 50, 'comment' => 'url参数'])
            ->addColumn('type', 'boolean', ['default'=>0, 'comment' => '菜单类型: 0,只作为菜单 1,权限认证+菜单'])
            ->addColumn('status', 'boolean', ['default'=>0, 'comment' => '状态: 0,不显示 1,显示'])
            ->addColumn('icon', 'string', ['limit' => 50, 'comment' => '菜单图标'])
            ->addColumn('remark', 'string', ['limit' => 255, 'comment' => '备注'])
            ->addColumn('list_order', 'integer', ['limit' => MysqlAdapter::INT_SMALL, 'default' => 0, 'comment' => '排序'])
            ->addColumn('rule_param', 'string', ['limit' => 255, 'comment' => '验证规则'])
            ->addColumn('request', 'string', ['limit' => 255, 'comment' => '请求方式（日志生成）'])
            ->addColumn('log_rule', 'string', ['limit' => 255, 'comment' => '日志规则'])
            ->addColumn('nav_id', 'integer', ['comment' => '前端导航ID'])
            ->addTimestamps('create_time')
            ->addIndex(['status'])
            ->addIndex(['model'])
            ->addIndex(['parent_id'])
            ->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('menu');
    }
}
