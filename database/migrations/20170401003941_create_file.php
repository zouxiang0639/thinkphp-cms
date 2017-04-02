<?php

use think\migration\Migrator;
use think\migration\db\Column;

class CreateFile extends Migrator
{
    /**
     * Migrate Up.
     */
    public function up()
    {
        $table = $this->table('file', ['id' => 'file_id', 'engine'=>'MyISAM', 'comment' => '文件上传库表']);
        $table->addColumn('path', 'string', ['limit' => 100, 'comment' => '路由'])
            ->addColumn('hash', 'string', ['limit' => 150, 'comment' => 'hash1'])
            ->addColumn('groups', 'boolean', ['default' => 1, 'comment' => '所属组: 1,image 2,file 3,video'])
            ->addColumn('name', 'string', ['limit' => 150, 'comment' => '文件名称'])
            ->addIndex(['hash'])
            ->save();

    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable('file');
    }
}
