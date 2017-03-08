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
    public function uploadPicture($name, $value = '', $options = [])
    {
        $picture    = 'http://localhost/ThinkC/admin/themes/simplebootx/Public/assets/images/default-thumbnail.png';
        $value      = !empty($value) ? $value : $picture;
        $html       ="  <input type='hidden' name='{$name}' id='{$name}' value=''>
                        <a href=\"javascript:upload_one_image('图片上传','#{$name}');\">
                            <img src='{$value}' id='{$name}-preview' height='40' style='cursor: hand' />
                        </a>
                        <input type='button' class='btn btn-small' onclick=\"
                            $('#{$name}-preview').attr('src','{$picture}');
                            $('#{$name}').val('');
                            return false;
                        \" value='取消图片'>";
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