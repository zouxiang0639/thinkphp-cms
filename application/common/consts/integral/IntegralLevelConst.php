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

    const ONE_RIGHTS = 1;
    const TWO_RIGHTS = 1.1;
    const THREE_RIGHTS = 1.2;


    /**
     * 积分等级权益
     * @return array
     */
    public static function rights()
    {
        return [
            self::ONE => self::ONE_RIGHTS,
            self::TWO => self::TWO_RIGHTS,
            self::THREE => self::THREE_RIGHTS,
        ];
    }

    /**
     * 会员等级名称
     * @return array
     */
    public static function desc()
    {
        return [
            self::ONE => self::ONE_DESC,
            self::TWO => self::TWO_DESC,
            self::THREE => self::THREE_DESC,
        ];
    }

    /**
     * 会员等级升级规则
     * @return array
     */
    public static function level()
    {
        return [
            self::ONE => self::ONE_LIMIT,
            self::TWO => self::TWO_LIMIT,
            self::THREE => self::THREE_LIMIT,
        ];
    }

    /**
     * 获取等级名称
     * @param $item
     * @return mixed
     */
    public static function getDesc($item)
    {
        return array_get(self::desc(), $item);
    }

    /**
     * 获取权益倍数
     * @param $item
     * @return mixed
     */
    public static function getRights($item)
    {
        return array_get(self::rights(), $item);
    }

    /**
     * 检查积分等级
     * @param $integrals
     * @return int|string
     */
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