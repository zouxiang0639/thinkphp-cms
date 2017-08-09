<?php
namespace app\common\consts\category;

/**
 * 绑定页面类型
 */
class CategoryBindPageConst
{
    const PAGE  = 1;
    const LINKS  = 2;

    const PAGE_DESC = '本地链接';
    const LINKS_DESC = '外部链接';

    public static function desc()
    {
        return [
            self::PAGE => self::PAGE_DESC,
            self::LINKS => self::LINKS_DESC,
        ];
    }

    public static function getDesc($item)
    {
        return array_get(self::desc(), $item);
    }
}