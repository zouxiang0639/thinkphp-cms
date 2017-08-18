<?php
namespace app\common\consts\banner;

/**
 * 扩展类型
 */
class BannerTypeConst
{
    const HOME_BANNER = 1;

    const HOME_BANNER_DESC = '幻灯片';

    public static function desc()
    {
        return [
            self::HOME_BANNER => self::HOME_BANNER_DESC,
        ];
    }

    public static function getDesc($item)
    {
        return array_get(self::desc(), $item);
    }
}