<?php

use think\migration\Migrator;
use Phinx\Db\Adapter\MysqlAdapter;

class CreateCart extends Migrator
{
    /**
     * Migrate Up.
     */
    public function up()
    {
        $table = $this->table('cart', ['id' => 'cart_id', 'engine'=>'InnoDB', 'comment' => '购物车']);
        $table->addColumn('user_id', 'integer', ['limit' => 11, 'comment' => '用户ID'])
            ->addColumn('amount', 'integer', ['limit' => 11, 'comment' => '价钱'])
            ->addColumn('goods_id', 'integer', ['limit' => 255, 'comment' => '商品ID'])
            ->addColumn('goods_name', 'string', ['limit' => 255, 'comment' => '商品名称'])
            ->addColumn('goods_num', 'integer', ['limit' => 11, 'comment' => '数量'])
            ->addColumn('picture', 'string', ['limit' => 255, 'comment' => '缩略图'])
            ->addTimestamps('create_time', 'update_time')
            ->addIndex(['cart_id'])
            ->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('cart');
    }
}
