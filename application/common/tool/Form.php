<?php
namespace app\common\tool;

use think\Config;
use think\form\FormBuilder;
use think\form\HtmlBuilder;

class Form extends FormBuilder
{
    public $ckeditorJs  = 0;
    public $dateJs      = 0;

    public function __construct(HtmlBuilder $html, $csrfToken)
    {
        parent::__construct($html, $csrfToken);
    }

    /**
     * 单张图片上传
     *
     * @param  string  $names
     * @param  string  $value
     * @param  array   $options
     * @return string
     */
    public function oneImage($names, $value = '', $options = [])
    {
        $name = str_replace("[", "-", $names);
        $name = str_replace("]", "", $name);
        $picture    = !empty($value) ? $value : Config::get('basic.default_picture');;

        $html       ="  <input type='hidden' name='{$names}' id='{$name}' value='{$value}'>
                        <a href=\"javascript:upload_one_file('图片上传','#{$name}');\">
                            <img src='{$picture}' id='{$name}-preview' height='40' style='cursor: hand' />
                        </a>
                        <input type='button' class='btn btn-default active' onclick=\"
                            $('#{$name}-preview').attr('src','{$picture}');
                            $('#{$name}').val('');
                            return false;
                        \" value='取消图片'>";
        return $html;
    }

    /**
     * 多张图片上传
     *
     * @param  string  $name
     * @param  string  $value
     * @param  array   $options
     * @return string
     */
    public function multiImage($names, $value = '', $options = [])
    {
        if(!is_array($value)){
            $value = json_decode($value, true);
        }
        $name = str_replace("[", "-", $names);
        $name = str_replace("]", "", $name);

        $html       = "<script type='text/html' id='{$name}-item-wrapper'>
                            <li id='{$name}{id}'>
                                <input id='{$name}-{id}' type='hidden' name='{$name}_1path[]' value='{filepath}'>
                                <input id='{$name}-{id}-name' type='text'
                                class='form-control text' name='{$name}_1name[]' value='{name}' title='图片名称'>
                                <img id='{$name}-{id}-preview' src='{filepath}' style='height:36px;width: 36px;'
                                onclick=\"parent.image_preview_dialog(this.src);\">
                                <a class='btn btn-primary' href=\"javascript:upload_one_file('图片上传','#{$name}-{id}');\">替换</a>
                                <a class='btn btn-default active' onclick=\"javascript:$('#{$name}{id}').remove();return false\">移除</a>
                            </li>
                      </script><ul id='{$name}' class='pic - list unstyled'>";

        if(is_array($value)){
            foreach ($value as $k => $v){
                $path = array_get($v, 'path');
                $names = array_get($v, 'name');

                $html  .= "<li id='{$name}{$k}'>
                                    <input id='{$name}-{$k}' type='hidden' name='{$name}_1path[]' value='{$path}'>
                                    <input id='{$name}-{$k}-name' type='text' class='form-control text' name='{$name}_1name[]'
                                    value='{$names}'
                                     title='图片名称'>
                                    <img id='{$name}-{$k}-preview' src='{$path}' style='height:36px;width: 36px;'
                                    onclick=\"parent.image_preview_dialog(this . src);\">
                                    <a class='btn btn-primary' href=\"javascript:upload_one_file('图片上传', '#{$name}-{$k}');\">替换</a>
                                    <a class='btn btn-default active' onclick=\"javascript:$('#{$name}{$k}') . remove();return false\">移除</a>
                                </li>";
            }
        }
        $html      .="</ul>
                        <input type='hidden' class='' name='{$name}' value=''>
                        <a  href=\"javascript:upload_multi_image('图片上传','#{$name}','{$name}-item-wrapper');\"
                        class='btn btn-primary'>选择图片</a>";
        return $html;
    }

    /**
     * 编辑器
     *
     * @param  string  $name
     * @param  string  $value
     * @param  array   $options
     * @return string
     */
    public function editor($name, $value = '', $options = [])
    {
        $html   = '';
        if($this->ckeditorJs === 0){
            $html .= '<script src="__STATIC__/default/ckeditor/ckeditor.js"></script>';
        }
        $html  .= "<textarea name='{$name}'  id='{$name}' class='form-control'>{$value}</textarea>
                <script>
                    CKEDITOR.replace('{$name}');
                </script>";
        $this->ckeditorJs  +=1;
        return  $html;
    }

    /**
     * 编辑器
     *
     * @param  string  $name
     * @param  string  $value
     * @param  array   $options
     * @return string
     */
    public function date($name, $value = '', $options = [])
    {
        $html   = '';
        if($this->ckeditorJs === 0){
            $html .= '<script src="__STATIC__/default/laydate/laydate.js"></script>';
        }
        $value  = empty($value) ? date('Y-m-d h:m:s') : $value;
        $html  .= "<input onclick=\"laydate({istime: true, format: 'YYYY-MM-DD hh:mm:ss'})\"
            type='text' class='form-control text' name='{$name}' value='{$value}'>";
        $this->dateJs +=1;
        return  $html;
    }

    /**
     * 多个单选
     *
     * @param  string  $name
     * @param  array  $list
     * @param  string  $value
     * @param  array   $options
     * @return string
     */
    public function radios($name, $list, $value = null, $options = [] )
    {
        $html = '';
        foreach((array)$list as $v){
            $checked    = $v==$value ? 1 : '' ;
            $checkbox   = parent::radio($name, $value, $checked, $options);
            $html       .="<label style='padding-top: 0px;padding-left: 0px;' class='checkbox-inline'>{$checkbox} {$v}</label>" ;
        }
        return $html;
    }

    /**
     * 多个复选
     *
     * @param  string  $name
     * @param  array  $list
     * @param  array  $value
     * @param  array   $options
     * @return string
     */
    public function checkboxs($name, $list, $value = [], $options = [] )
    {
        $html = '';
        foreach((array)$list as $k => $v){
            $chacked    = array_search($k,(array)$value);
            $checked    = $chacked !== false ? 1 : '' ;
            $checkbox   = parent::checkbox($name.'[]', $k, $checked, ['style' => 'margin-top: 2px;' ]);
            $html      .="<label style='padding-top: 0px; padding-left: 0px;' class='checkbox-inline'>{$checkbox}{$v}</label>" ;
        }
        return $html;
    }
}