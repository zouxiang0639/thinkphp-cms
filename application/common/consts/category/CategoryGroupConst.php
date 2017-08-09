<?php
namespace app\common\consts\category;

/**
 * 导航分组
 */
class CategoryGroupConst
{
    const HEADER  = 1;
    const FOOTER  = 2;

    const HEADER_DESC = '头部导航';
    const FOOTER_DESC = '底部导航';

    public static function desc()
    {
        return [
            self::HEADER => self::HEADER_DESC,
            self::FOOTER => self::FOOTER_DESC,
        ];
    }

    public static function getDesc($item)
    {
        return array_get(self::desc(), $item);
    }
}