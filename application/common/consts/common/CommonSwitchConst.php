<?php
namespace app\common\consts\common;

/**
 * 开关
 */
class CommonSwitchConst
{
    const ON  = 1;
    const OFF  = 2;

    const ON_DESC = '开启';
    const OFF_DESC = '关闭';

    public static function desc()
    {
        return [
            self::ON => self::ON_DESC,
            self::OFF => self::OFF_DESC,
        ];
    }

    public static function getDesc($item)
    {
        return array_get(self::desc(), $item);
    }
}