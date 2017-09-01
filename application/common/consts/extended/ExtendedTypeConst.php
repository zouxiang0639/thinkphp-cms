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

    const FIELD_DESC = '字段类型';
    const MYSQL_DESC = 'mysql类型';
    const FORM_DESC  = '表单扩展';

    public static function desc()
    {
        return [
            self::FIELD => self::FIELD_DESC,
            self::MYSQL => self::MYSQL_DESC,
            self::FORM  => self::FORM_DESC,
        ];
    }

    public static function getDesc($item)
    {
        return array_get(self::desc(), $item);
    }
}