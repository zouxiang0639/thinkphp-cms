<?php
namespace app\common\consts\common;

/**
 * 导航状态类型
 */
class CommonStatusConst
{
    const SHOW  = 1;
    const HIDE  = 2;
    const ADMIN_SHOW = 3;

    const SHOW_DESC = '显示';
    const HIDE_DESC = '隐藏';
    const ADMIN_SHOW_DESC = '管理员可见';

    public static function desc()
    {
        return [
            self::SHOW => self::SHOW_DESC,
            self::HIDE => self::HIDE_DESC,
            self::ADMIN_SHOW => self::ADMIN_SHOW_DESC
        ];
    }

    public static function getDesc($item)
    {
        return array_get(self::desc(), $item);
    }
}