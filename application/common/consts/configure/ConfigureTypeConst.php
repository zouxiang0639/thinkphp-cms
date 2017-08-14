<?php
namespace app\common\consts\configure;

/**
 * 扩展类型
 */
class ConfigureTypeConst
{
    const BASIC  = 1;
    const EMAIL  = 2;

    const BASIC_DESC = '基本配置';
    const EMAIL_DESC = '邮箱配置';

    public static function desc()
    {
        return [
            self::BASIC => self::BASIC_DESC,
            self::EMAIL => self::EMAIL_DESC
        ];
    }

    public static function getDesc($item)
    {
        return array_get(self::desc(), $item);
    }
}