<?php
namespace app\common\model;

use think\Db;

class ExtendedModel extends BasicModel
{
    protected $name = 'extended';

    /**
     * 创建数据库
     * @return mixed
     */
    public function createTabel()
    {
        if($this->group == 2){
            $sql = "CREATE TABLE `{$this->mysql_name}` (
                      `id` int(10) NOT NULL AUTO_INCREMENT,
                      `extended_id` int(10) NOT NULL,
                      PRIMARY KEY (`id`)
                    ) ENGINE=MyISAM CHARSET=utf8 COMMENT='{$this->title}'";
            try{
                Db::execute($sql);
            } catch (\Exception $e) {
                $this->delete();
                dump($sql);
                dump($e);
            }

            return $this;
        }else{
            return empty($this->extended_id) ? false : true;
        }
    }

    /**
     * 创建字段
     * @return mixed
     */
    public function createFields()
    {
        $parent = ExtendedModel::get($this->parent_id);

        if($this->group == 2){
            $type   = lang('mysql fields type');
            $type   = $type[$this->mysql_fields_type];
            $sql    = "alter table {$parent['mysql_name']} add `{$this->data['name']}` ";

            //设置字段长度 过滤不需要字段长度类型
            if(in_array($type, ['timestamp','mediumtext','longtext','text','tinytext','datetime'])){
                $sql   .= $type;
            }else{
                $sql   .= $type."({$this->mysql_fields_length})";
            }

            //设置为非NUll
            $sql    .= ' NOT NULL ';

            //设置默认值
            if($this->mysql_fields_default){
                $sql    .= "DEFAULT '{$this->mysql_fields_default}'";
            }

            //设置字段注释
            $sql    .= " COMMENT '{$this->data['title']}'";
            try{
                Db::execute($sql);
            } catch (\Exception $e) {

                //删除当前
                $this->delete();

                //打印错误信息
                dump($sql);
                dump($e);
            }

            return $this;
        }else{
            return empty($this->extended_id) ? false : true;
        }
    }

    /**
     * 删除数据库字段
     * @return mixed
     */
    public function fieldsDelete()
    {
        $parent = ExtendedModel::get($this->parent_id);
        $sql    = "alter table `{$parent['mysql_name']}` drop column {$this->data['name']}";
        try{
            Db::execute($sql);
        } catch (\Exception $e) {

            //打印错误信息
            dump($sql);
            dump($e);
        }

        return $this;
    }

    /**
     * 查看创建表信息
     * @return mixed
     */
    public function showCreateTabel()
    {
        $table  = Db::query("SHOW CREATE TABLE {$this->mysql_name}");
        return explode(',',$table[0]['Create Table']);
    }

    /**
     * 删除子集数据
     * @return mixed
     */
    public function deleteChild(){
        ExtendedModel::where(['parent_id'=>$this->extended_id])->delete();
        return $this;
    }

    /**
     * 删除数据库
     * @return mixed
     */
    public function deleteTabel()
    {
        if($this->group == 2) {
            $sql = "DROP TABLE {$this->mysql_name}";
            try {
                Db::execute($sql);
            } catch (\Exception $e) {
                //打印错误信息
                dump($sql);
                dump($e);
            }
        }
        return $this;
    }
}