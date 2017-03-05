<?php
namespace app\common\tool;

use think\form\HtmlBuilder;

class Tool
{
    public static function get($name)
    {
       switch($name){
            case 'helper':
                return new Helper();
            case 'form':
                return new Form(new HtmlBuilder(),['name' => '__token__','type' => 'md5']);
           case 'file':
                return new File(CONF_PATH.'extra'.DS.'basic.php');
            default:
                return '没有这个工具';
        }

    }
}