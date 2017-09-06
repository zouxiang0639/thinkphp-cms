<?php
namespace app\common\consts\label;

/**
 * 标签类型
 */
class LabelTypeConst
{
    const LABEL = 1;

    const LABEL_DESC = '标签';

    public static function desc()
    {
        return [
            self::LABEL => self::LABEL_DESC,
        ];
    }

    public static function getDesc($item)
    {
        return array_get(self::desc(), $item);
    }
}