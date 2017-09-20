<?php
namespace app\common\consts\integral;

/**
 * 开关
 */
class IntegralTypeConst
{
    const ADD  = 1;
    const REDUCE  = 2;

    const ADD_DESC = '+';
    const REDUCE_DESC = '-';

    public static function desc()
    {
        return [
            self::ADD => self::ADD_DESC,
            self::REDUCE => self::REDUCE_DESC,
        ];
    }

    public static function getDesc($item)
    {
        return array_get(self::desc(), $item);
    }
}