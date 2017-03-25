<?php
namespace app\common\model;

use app\common\tool\Tool;
use think\Config;
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
            $mysqlName      = Config::get('database.prefix').$this->data['name'];
            $sql = "CREATE TABLE `{$mysqlName}` (
                      `extended_id` int(10) NOT NULL,
                      PRIMARY KEY (`extended_id`)
                    ) ENGINE=MyISAM CHARSET=utf8 COMMENT='{$this->title}'";
            try{
                Db::execute($sql);

            } catch (\Exception $e) {
                $this->delete();
                dump($sql);
                dump($e);
            }

            //生成模型
            $mysqlName  = explode('_', $this->data['name'].'_Model');
            $modelName  = array_map(function($arr){
                return ucfirst($arr);
            }, $mysqlName);
            $modelName  = implode('', $modelName);
            $modelPath  = APP_PATH.'manage'.DS.'model'.DS.$modelName.EXT;
            $content = '<?php
namespace app\manage\model;

use think\Model;

class '.$modelName.' extends Model
{
    public $name = "'.$this->data['name'].'";
}';

            $modelBuilder = Tool::get('file')->writeFile($modelPath,$content);
            if(!$modelBuilder){
                dump('没有权限生成'.$modelName.'模型');
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
            $mysqlName      = Config::get('database.prefix').$parent['name'];
            $type   = lang('mysql fields type');
            $type   = $type[$this->mysql_fields_type];
            $sql    = "alter table {$mysqlName} add `{$this->data['name']}` ";

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
        $mysqlName      = Config::get('database.prefix').$parent['name'];
        $sql    = "alter table `{$mysqlName}` drop column {$this->data['name']}";
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
        $mysqlName      = Config::get('database.prefix').$this->data['name'];
        $table          = Db::query("SHOW CREATE TABLE {$mysqlName}");
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
            $mysqlName      = Config::get('database.prefix').$this->data['name'];
            $sql = "DROP TABLE {$mysqlName}";
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


    /**
     * 生成form表单
     * @return mixed
     */
    public static function formBuilder($id, $data='')
    {
        if($id==0){
            return '';
        }

        $html   = '';
        //获取
        $extend     = self::where(['parent_id'=>$id])
            ->order(["sort" => "desc", 'extended_id' => 'asc'])
            ->select();
        $input_type = lang('form type');
        foreach((object)$extend as $v){
            //使用表单枚举生成<form> 标签支持
            $input  =  Tool::get('helper')->formEnum(
                array_get($input_type,$v['input_type']),        //表单类型
                'extend['.$v['name'].']',                       //变量名称
                array_get($data,$v['name']),                    //置变量的值
                ['class' => 'form-control text'],               //其他属性
                json_decode($v['input_value'])                  //需要生成多个 如select
            );

            $html  .= "<tr>
                            <th>{$v['title']}</th>
                            <th>
                                {$input}
                                <span style='padding-left: 10px'>{$v['comment']}</span>
                            </th>
                        </tr>";
        }

        return $html;
    }

    /**
     * 数据扩展组
     * @return mixed
     */
    public static function extendedGroup()
    {
        $extended   = self::where(['parent_id'=>0])->column('extended_id,group,title');
        $default    = [0 => '请选择'];
        $group      = [0 => $default, 1 => $default, 2 => $default];
        foreach((array)$extended as $v){
            $group[$v['group']][$v['extended_id']]  = $v['title'];
            $group[0][$v['extended_id']]            = $v['title'];
        }
        return $group;
    }

}