<?php
namespace app\common\consts\integral;

/**
 * 积分等级
 */
class IntegralLevelConst
{
    const ONE  = '1';
    const TWO  = '2';
    const THREE  = '3';

    const ONE_DESC = '普通会员';
    const TWO_DESC = '签约会员';
    const THREE_DESC = 'VIP会员';

    const ONE_LIMIT = [0, 10000];
    const TWO_LIMIT = [10000, 20000];
    const THREE_LIMIT = [20000, 100000000000000];

    public static function desc()
    {
        return [
            self::ONE => self::ONE_DESC,
            self::TWO => self::TWO_DESC,
            self::THREE => self::THREE_DESC,
        ];
    }

    public static function level()
    {
        return [
            self::ONE => self::ONE_LIMIT,
            self::TWO => self::TWO_LIMIT,
            self::THREE => self::THREE_LIMIT,
        ];
    }

    public static function getDesc($item)
    {
        return array_get(self::desc(), $item);
    }

    public static function checkLevel($integrals)
    {
        $level = static::level();

        foreach($level as $key => $value) {
            list($min, $max) = $value;
            if($integrals >= $min && $integrals <= $max){
                return $key;
            }
        }

    }
}