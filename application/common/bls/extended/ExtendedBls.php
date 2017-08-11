<?php
namespace app\common\bls\extended;


use app\common\bls\extended\model\ExtendedModel;
use app\common\consts\extended\ExtendedMysqlFieldsTypeConst;
use app\common\consts\extended\ExtendedTypeConst;
use app\common\tool\Tool;
use think\Config;
use think\Db;

class ExtendedBls
{
    public static function getExtendedList($where = '', $limit = 20)
    {
        return ExtendedModel::where($where)->paginate($limit, '', [
            'query' => input()
        ]);
    }

    public static function createExtended($date)
    {

        $model = new ExtendedModel();
        $model->title   = $date['title'];
        $model->type    = $date['type'];
        $model->name    = $date['name'];
        $model->comment = $date['comment'];
        $model->save();

        self::createTabel($model);

        return $model;
    }

    public static function getExtended($where = '')
    {
        return ExtendedModel::where($where)->select();
    }

    public static function getOneExtended($where = '')
    {
        return ExtendedModel::where($where)->find();
    }

    public static function createExtendedFields($data)
    {

        $model = new ExtendedModel();
        $model->title   = $data['title'];
        $model->name    = $data['name'];
        $model->sort    = $data['sort'];
        $model->comment = $data['comment'];
        $model->input_type = $data['input_type'];
        $model->input_value = $data['input_value'];
        $model->parent_id = $data['parent_id'];
        $model->save();

        return $model;
    }

    public static function createDataType($data)
    {
        $model = new ExtendedModel();
        $model->title   = $data['title'];
        $model->name    = $data['name'];
        $model->mysql_fields_type    = $data['mysql_fields_type'];
        $model->mysql_fields_length    = $data['mysql_fields_length'];
        $model->mysql_fields_default    = $data['mysql_fields_default'];
        $model->sort    = $data['sort'];
        $model->comment = $data['comment'];
        $model->input_type = $data['input_type'];
        $model->input_value = $data['input_value'];
        $model->parent_id = $data['parent_id'];
        $model->type = ExtendedTypeConst::MYSQL;

        $model->save();

        return self::createFields($model);

    }

    /**
     * 删除数据扩展
     * @param  ExtendedModel $model
     * @return mixed
     */
    public static function deleteExtended(ExtendedModel $model)
    {
        $model->deleteChild();

        if($model->type == ExtendedTypeConst::MYSQL){
            self::deleteTabel($model);
        }

        return $model->delete();

    }

    /**
     * 数据库不同的类型做不同的长度判断
     *
     * @param  string    $type
     * @return string
     */
    public static function checkFieldsType($type)
    {
        $validate   = '';

        //根据不同的类型做长度判断
        switch(ExtendedMysqlFieldsTypeConst::getDesc($type)){
            case 'tinyint':         //存储大小为 1 字节。
                $validate   = '|require|between:1,4';
                break;
            case 'smallint':        //存储大小为 2 个字节。
                $validate   = '|require|between:1,6';
                break;
            case 'int':             //存储大小为 4 个字节。
                $validate   = '|require|between:1,11';
                break;
            case 'bigint':          //存储大小为 8 个字节。
                $validate   = '|require|between:1,20';
                break;
            case 'varchar':         //存储大小为 1 个字节, 大于 255 为2个字节
                $validate   = '|require|between:1,65535';
                break;
            case 'char':            //存储大小为 1 个字节, 建议存储固定长度的字符串
                $validate   = '|require|between:1,255';
                break;
            case 'float':
                $validate   = '|require';
                break;
            case 'decimal':
                $validate   = '|require';
                break;
            default:
        }

        if($validate){
            return $validate;
        }

        return '';
    }

    /**
     * 删除数据库字段
     * @param  ExtendedModel $model
     * @return mixed
     */
    public static function mysqlFieldsDelete(ExtendedModel $model)
    {

        $parent = ExtendedModel::get($model->parent_id);
        $mysqlName      = Config::get('database.prefix').$parent['name'];
        $sql    = "alter table `{$mysqlName}` drop column {$model->name}";
        try{
            Db::execute($sql);
        } catch (\Exception $e) {

            //打印错误信息
            dump($sql);
            dump($e);
        }

        return $model->delete();
    }

    /**
     * 查看创建表信息
     * @param ExtendedModel $model
     * @return mixed
     */
    public static function showCreateTabel(ExtendedModel $model)
    {
        $mysqlName      = Config::get('database.prefix').$model->name;
        $table          = Db::query("SHOW CREATE TABLE {$mysqlName}");
        return explode(',',$table[0]['Create Table']);
    }

    /**
     * 删除数据库
     * @param  ExtendedModel $model
     * @return mixed
     */
    private static function deleteTabel(ExtendedModel $model)
    {
        $mysqlName      = Config::get('database.prefix').$model->name;
        $sql = "DROP TABLE {$mysqlName}";
        try {
            Db::execute($sql);
        } catch (\Exception $e) {
            //打印错误信息
            dump($sql);
            dump($e);
        }

        return $model;
    }

    /**
     * 创建数据库
     * @param ExtendedModel $model
     * @return mixed
     */
    private static function createTabel(ExtendedModel $model)
    {
        if($model->type == ExtendedTypeConst::MYSQL){
            $mysqlName      = Config::get('database.prefix').$model->name;

            $sql = "CREATE TABLE `{$mysqlName}` (
                      `extended_id` int(10) NOT NULL,
                      PRIMARY KEY (`extended_id`)
                    ) ENGINE=MyISAM CHARSET=utf8 COMMENT='{$model->title}'";

            try{

                Db::execute($sql);

            } catch (\Exception $e) {
                $model->delete();
                dump($sql);
                dump($e);
            }

            //生成模型
            $mysqlName  = explode('_', $model->name.'_Model');
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
    public $name = "'.$model->name.'";
}';

            $modelBuilder = Tool::get('file')->writeFile($modelPath,$content);
            if(!$modelBuilder){
                dump('没有权限生成'.$modelName.'模型');
            }

            return $model;
        }else{
            return empty($model->extended_id) ? false : true;
        }
    }

    /**
     * 创建字段
     * @param ExtendedModel $model
     * @return mixed
     */
    private static function createFields(ExtendedModel $model)
    {
        $parent = ExtendedModel::get($model->parent_id);
        if($model->type == ExtendedTypeConst::MYSQL){
            $mysqlName      = Config::get('database.prefix').$parent['name'];
            $type   = lang('mysql fields type');
            $type   = $type[$model->mysql_fields_type];
            $sql    = "alter table {$mysqlName} add `{$model->name}` ";

            //设置字段长度 过滤不需要字段长度类型
            if(in_array($type, ['timestamp','mediumtext','longtext','text','tinytext','datetime'])){
                $sql   .= $type;
            }else{
                $sql   .= $type."({$model->mysql_fields_length})";
            }

            //设置为非NUll
            $sql    .= ' NOT NULL ';

            //设置默认值
            if($model->mysql_fields_default){
                $sql    .= "DEFAULT '{$model->mysql_fields_default}'";
            }

            //设置字段注释
            $sql    .= " COMMENT '{$model->title}'";
            try{
                Db::execute($sql);
            } catch (\Exception $e) {

                //删除当前
                $model->delete();

                //打印错误信息
                dump($sql);
                dump($e);
            }

            return $model;
        }else{
            return empty($model->extended_id) ? false : true;
        }
    }


}