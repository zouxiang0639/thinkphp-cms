<?php
namespace app\common\consts\integral;

/**
 * 积分规则方法
 */
class IntegralRuleMethodConst
{
    const ADD  = 'add';

    const ADD_DESC = '添加积分';

    public static function desc()
    {
        return [
            self::ADD => self::ADD_DESC,
        ];
    }

    public static function getDesc($item)
    {
        return array_get(self::desc(), $item);
    }
}