<?php
namespace app\common\tool;

class Tool
{
    public static function get($name)
    {
        switch($name){
            case 'helper':
               return new Helper();
                break;
            default:
                return '没有这个工具';
        }
    }
}