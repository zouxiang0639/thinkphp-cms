<?php
namespace app\common\tool;

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
        return md5(md5($password).$str);
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
            case 'text':
                return Tool::get('form')->text($name, $value, $options);
            case 'select':
                return Tool::get('form')->select($name, $list, $value, $options);
            case 'checkbox':

                foreach($list as $v){
                    $checked    = !empty($value[$v]) ? 1 : '' ;
                    $checkbox   = Tool::get('form')->checkbox($name.'[]', $v, $checked, ['style' => 'margin-top: 2px;' ]);
                    $html       .="<label style='padding-top: 0px;' class='checkbox-inline'>{$checkbox}{$v}</label>" ;
                }
                return $html;

            case 'radio':
                foreach($list as $v){

                    $checked    = $v==$value ? 1 : '' ;
                    $checkbox   = Tool::get('form')->radio($name, $v, $checked, ['style' => 'margin-top: 2px;' ]);
                    $html       .="<label style='padding-top: 0px;' class='checkbox-inline'>{$checkbox} {$v}</label>" ;

                }
                return $html;
            case 'textarea':
                return Tool::get('form')->textarea($name, $value,  array_merge(['clos' => '30', 'rows' => 3], $options));
            case 'password':
                return Tool::get('form')->password($name, $options);
            default:
                return '没有这个表单类型';
        }
    }

}