<?php

use think\migration\Migrator;
use think\migration\db\Column;

class CreateBanner extends Migrator
{
    /**
     * Migrate Up.
     */
    public function up()
    {
        $table = $this->table('banner', ['id' => 'banner_id', 'engine'=>'MyISAM', 'comment' => '幻灯片表']);
        $table->addColumn('title', 'string', ['limit' => 100, 'comment' => '标题'])
            ->addColumn('picture', 'string', ['limit' => 100, 'comment' => '图片'])
            ->addColumn('links', 'string', ['limit' => 150, 'comment' => '链接'])
            ->addColumn('groups', 'boolean', ['default' => 1, 'comment' => '所属组'])
            ->addColumn('comment', 'string', ['limit' => 255, 'comment' => '描述'])
            ->addTimestamps('create_time')
            ->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('banner');
    }
}
