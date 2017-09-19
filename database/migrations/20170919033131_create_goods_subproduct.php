<?php

use think\migration\Migrator;
use Phinx\Db\Adapter\MysqlAdapter;

class CreateGoodsSubproduct extends Migrator
{
    /**
     * Migrate Up.
     */
    public function up()
    {
        $table = $this->table('goods_subproduct', ['id' => 'goods_subproduct_id', 'engine'=>'InnoDB', 'comment' => '附属产品']);
        $table->addColumn('title', 'string', ['limit' => 255, 'comment' => '标题'])
            ->addColumn('goods_id', 'integer', ['limit' => 11, 'comment' => '商品ID'])
            ->addColumn('price', 'integer', ['limit' => 11, 'comment' => '价格'])
            ->addColumn('type', 'integer', ['limit' => 11, 'comment' => '关联 : extended_id'])
            ->addColumn('extend', 'string', ['limit' => MysqlAdapter::TEXT_MEDIUM, 'comment' => '扩展属性内容'])
            ->addTimestamps('create_time', 'update_time')
            ->addIndex(['goods_subproduct_id'])
            ->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('goods_subproduct');
    }
}

