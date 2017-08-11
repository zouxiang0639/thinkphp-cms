<?php
namespace app\common\consts\extended;

/**
 * 数据库字段类型
 */
class ExtendedMysqlFieldsTypeConst
{
    const TINYINT  = 1;
    const INT  = 2;
    const MEDIUMTEXT  = 3;
    const TIMESTAMP  = 4;
    const FLOAT  = 5;

    const TINYINT_DESC = 'tinyint';
    const INT_DESC = 'int';
    const MEDIUMTEXT_DESC = 'mediumtext';
    const TIMESTAMP_DESC = 'timestamp';
    const FLOAT_DESC = 'float';

    public static function desc()
    {
        return [
            self::TINYINT => self::TINYINT_DESC,
            self::INT => self::INT_DESC,
            self::MEDIUMTEXT => self::MEDIUMTEXT_DESC,
            self::TIMESTAMP => self::TIMESTAMP_DESC,
            self::FLOAT => self::FLOAT_DESC,
        ];
    }

    public static function getDesc($item)
    {
        return array_get(self::desc(), $item);
    }
}