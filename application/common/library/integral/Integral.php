<?php
namespace app\common\library\integral;

/**
 * 积分管理
 */
class Integral
{

    /**
     * 添加积分
     * @param $id
     * @param $user_id
     * @return bool|string
     */
    public static function addIntegral($id, $user_id)
    {
        return (new IntegralRule())->checkIntegralRule($id, $user_id);
    }

    /**
     * 减积分
     * @param $id
     * @param $user_id
     * @param $title
     * @return bool|string
     */
    public static function reduceIntegral($id, $user_id, $title)
    {
        return (new IntegralRule())->reduceIntegral($id, $user_id, $title);
    }
}