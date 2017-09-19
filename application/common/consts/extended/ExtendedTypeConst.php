<?php
namespace app\common\consts\extended;

/**
 * 扩展类型
 */
class ExtendedTypeConst
{
    const FIELD  = 1;
    const MYSQL  = 2;
    const FORM   = 3;
    const SUB_PRODUCT   = 4;

    const FIELD_DESC = '字段类型';
    const MYSQL_DESC = 'mysql类型';
    const FORM_DESC  = '表单扩展';
    const SUB_PRODUCT_DESC  = '副产品扩展';

    public static function desc()
    {

        $arr[self::FIELD] = self::FIELD_DESC;
        $arr[self::MYSQL] = self::MYSQL_DESC;
        $arr[self::FORM] = self::FORM_DESC;

        if(config('extend.sub_product')){
            $arr[self::SUB_PRODUCT] = self::SUB_PRODUCT_DESC;
        }

        return $arr;
    }

    public static function getDesc($item)
    {
        return array_get(self::desc(), $item);
    }
}