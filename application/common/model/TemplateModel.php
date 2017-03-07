<?php
namespace app\common\model;

class TemplateModel extends BasicModel
{
    public $name = 'Template';

    /**
     * 模版类型文件
     *
     * @param  int      $selected
     * @param  string   $terminal
     * @return string
     */
    public static function tplTypeLife($type = ['分类页面', '通用页面'])
    {
        return self::where(['type' => ['in', $type]])->column('concat(title, template_file)', 'template_file');
    }
}