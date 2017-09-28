<?php

use think\migration\Migrator;
use Phinx\Db\Adapter\MysqlAdapter;

class UpdateUserByIntegral extends Migrator
{

    /**
     * Migrate Up.
     */
    public function up()
    {
        $table = $this->table('user');
        $table->addColumn('integral', 'integer', ['limit' => 11, 'comment' => '积分'])
            ->addColumn('total_integral', 'integer', ['limit' => 11, 'comment' => '总积分'])
            ->addColumn('level', 'boolean', ['default' => 1, 'comment' => '会员等级'])
            ->addColumn('Destroy_integral_time', 'datetime', ['comment' => '销毁积分时间'])
            ->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $table = $this->table('user');
        $table->removeColumn('level')
            ->removeColumn('integral')
            ->removeColumn('total_integral')
            ->removeColumn('Destroy_integral_time')
            ->save();
    }
}
