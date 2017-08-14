<?php
namespace app\common\consts\common;

/**
 * 性别
 */
class CommonSexConst
{
    const MALE  = 1;
    const FEMALE  = 2;

    const MALE_DESC = '男';
    const FEMALE_DESC = '女';

    public static function desc()
    {
        return [
            self::MALE => self::MALE_DESC,
            self::FEMALE => self::FEMALE_DESC,
        ];
    }

    public static function getDesc($item)
    {
        return array_get(self::desc(), $item);
    }
}