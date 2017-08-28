<?php
namespace app\common\consts\user;

/**
 * 用户状态
 */
class UserStatusConst
{
    const ABLE  = 1;
    const UNABLE  = 2;

    const ABLE_DESC = '正常';
    const UNABLE_DESC = '禁用';

    public static function desc()
    {
        return [
            self::ABLE => self::ABLE_DESC,
            self::UNABLE => self::UNABLE_DESC,
        ];
    }

    public static function getDesc($item)
    {
        return array_get(self::desc(), $item);
    }
}