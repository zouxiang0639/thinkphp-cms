<?php
namespace app\common\consts\info;

/**
 * 推荐类型
 */
class InfoRecommendationConst
{
    const HOT  = 1;

    const HOT_DESC = '热门推荐';

    public static function desc()
    {
        return [
            self::HOT => self::HOT_DESC,
        ];
    }

    public static function getDesc($item)
    {
        return array_get(self::desc(), $item);
    }
}