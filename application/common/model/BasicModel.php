<?php
namespace app\common\model;

use think\Model;

class BasicModel extends Model
{


    /**
     * 重组数组
     * [
     * 'photos_1path'   =>['','','']
     * 'photos_1name'   =>['','','']
     * 'photos'         => ''
     * ]
     *
     * 重组后
     * [
     * 'photos'         => [
     *     0    => [
     *              'path'   => '',
     *              'name'   => '',
     *          ]
     *      ]
     * ]
     *
     * @param  array    $data
     * @param  array    $name
     * @param  string    $type
     * @return mixed
     */
    public static function recombinantArray($data, $name, $type = 'json')
    {

        $arr        = [];
        $keyName    = [];
        $array = [];
        foreach((array)$name as $name){

            //查询出name_1 匹配的字段
            foreach($data as $key => $value){
                $strpos     = strpos($key, $name.'_1');
                if($strpos !== false){
                    unset($data[$key]);
                    $key            = explode('_1', $key);
                    $keyName[]      = $key[1];
                    $arr[$key[1]]   = $value;
                }
            }

            //重组数组
            foreach(current($arr) as $k => $v){
                foreach($keyName as $key){

                    $array[$k][$key] = $arr[$key][$k];
                }
            }
            unset($arr);

            //选择输出的类型
            switch($type){
                case 'json':
                    $data[$name] = json_encode($array);
                    break;
                case 'array':
                default:
                $data[$name] = $array;
            }
            unset($array);
        }
        return $data;
    }

}