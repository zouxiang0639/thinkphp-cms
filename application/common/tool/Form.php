<?php
namespace app\common\tool;

use think\form\FormBuilder;
use think\form\HtmlBuilder;

class Form extends FormBuilder
{

    public function __construct(HtmlBuilder $html, $csrfToken)
    {
        parent::__construct($html, $csrfToken);
    }

    /**
     * 单张图片上传
     *
     * @param  string  $name
     * @param  string  $value
     * @param  array   $options
     * @return string
     */
    public function oneImage($name, $value = '', $options = [])
    {
        $picture    = 'http://localhost/ThinkC/admin/themes/simplebootx/Public/assets/images/default-thumbnail.png';
        $value      = !empty($value) ? $value : $picture;
        $html       ="  <input type='hidden' name='{$name}' id='{$name}' value=''>
                        <a href=\"javascript:upload_one_image('图片上传','#{$name}');\">
                            <img src='{$value}' id='{$name}-preview' height='40' style='cursor: hand' />
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
    public function multiImage($name, $value = '', $options = [])
    {
        if(!is_array($value)){
            $value = json_decode($value, true);
        }


        $html       = "<script type='text/html' id='{$name}-item-wrapper'>
                            <li id='{$name}{id}'>
                                <input id='{$name}-{id}' type='hidden' name='{$name}_1path[]' value='{filepath}'>
                                <input id='{$name}-{id}-name' type='text'
                                class='form-control text' name='{$name}_1name[]' value='{name}' title='图片名称'>
                                <img id='{$name}-{id}-preview' src='{filepath}' style='height:36px;width: 36px;'
                                onclick=\"parent.image_preview_dialog(this.src);\">
                                <a class='btn btn-primary' href=\"javascript:upload_one_image('图片上传','#{$name}-{id}');\">替换</a>
                                <a class='btn btn-default active' onclick=\"javascript:$('#{$name}{id}').remove();return false\">移除</a>
                            </li>
                      </script><ul id='{$name}' class='pic - list unstyled'>";

        if(is_array($value)){
            foreach ($value as $k => $v){
                $html  .= "<li id='{$name}{$k}'>
                                    <input id='{$name}-{$k}' type='hidden' name='{$name}_1path[]' value='{$v['path']}'>
                                    <input id='{$name}-{$k}-name' type='text' class='form-control text' name='{$name}_1name[]'
                                    value='{$v['name']}'
                                     title='图片名称'>
                                    <img id='{$name}-{$k}-preview' src='{$v['path']}' style='height:36px;width: 36px;'
                                    onclick=\"parent.image_preview_dialog(this . src);\">
                                    <a class='btn btn-primary' href=\"javascript:upload_one_image('图片上传', '#{$name}-{$k}');\">替换</a>
                                    <a class='btn btn-default active' onclick=\"javascript:$('#{$name}{$k}') . remove();return false\">移除</a>
                                </li>";
            }
        }
        $html      .="</ul>
                        <input type='hidden' class='' name='{$name}' value='array'>
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
        return  "<textarea name='{$name}'  id='{$name}' class='form-control'>{$value}</textarea>
                <script>
                    CKEDITOR.replace('{$name}');
                </script>";
    }
}