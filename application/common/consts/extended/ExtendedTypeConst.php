<?php
namespace app\common\consts\extended;

/**
 * 扩展类型
 */
class ExtendedTypeConst
{
    const FIELD  = 1;
    const MYSQL  = 2;

    const FIELD_DESC = '字段类型';
    const MYSQL_DESC = 'mysql类型';

    public static function desc()
    {
        return [
            self::FIELD => self::FIELD_DESC,
            self::MYSQL => self::MYSQL_DESC,
        ];
    }

    public static function getDesc($item)
    {
        return array_get(self::desc(), $item);
    }
}