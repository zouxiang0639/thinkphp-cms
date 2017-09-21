<?php
namespace app\common\tool;

use app\common\consts\common\CommonFormInputConst;
use think\Config;

class Helper
{

    /**
     * 双层md5+自定义字符串加密
     *
     * @param  string  $password
     * @return string
     */
    public function getMd5($password)
    {
        $str    = Config::get('login_md5');
        return md5($password.$str);
    }

    /**
     * form生成器枚举
     *
     * @param  string  $password
     * @return string
     */
    public  function formEnum($type, $name, $value = null, $options = [], $list = []){
        $html = '';
        switch($type){
            case CommonFormInputConst::TEXT:
                return Tool::get('form')->text($name, $value, $options);
            case CommonFormInputConst::SELECT:
                return Tool::get('form')->select($name, $list, $value, $options);
            case CommonFormInputConst::CHECKBOX:
                return Tool::get('form')->checkboxs($name, $list, $value, ['style' => 'margin-top: 2px;' ]);
            case CommonFormInputConst::RADIOS:
                return Tool::get('form')->radios($name, $list, $value, ['style' => 'margin-top: 2px;' ]);
            case CommonFormInputConst::PASSWORD:
                return Tool::get('form')->password($name, $options);
            case CommonFormInputConst::TEXTAREA:
                return Tool::get('form')->textarea($name, $value,  array_merge(['clos' => '30', 'rows' => 3], $options));
            case CommonFormInputConst::ONE_IMAGE:
                return Tool::get('form')->oneImage($name, $value);
            case CommonFormInputConst::ONE_FILE:
                return Tool::get('form')->onefile($name, $value);
            case CommonFormInputConst::ONE_AUDIO:
                return Tool::get('form')->oneAudio($name, $value);
            case 'multiimage':
                return Tool::get('form')->multiImage($name, $value);
            case 'editor':
                return Tool::get('form')->editor($name, $value);
            case 'editor':
                return Tool::get('form')->editor($name, $value);
            default:
                return '没有这个表单类型';
        }
    }


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
            if(!empty($arr)){
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
        }
        return $data;
    }


}