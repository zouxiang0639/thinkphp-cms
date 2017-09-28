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
     * @param $user_id
     * @param $integral
     * @param $title
     * @return bool|string
     */
    public static function reduceIntegral($user_id, $integral, $title)
    {
        return (new IntegralRule())->reduceIntegral($user_id, $integral, $title);
    }

    /**
     * 手动添加积分
     * @param $user_id
     * @param $integral
     * @param $title
     * @return bool|string
     */
    public static function manuallyAddIntegral($user_id, $integral, $title)
    {
        return (new IntegralRule())->manuallyAddIntegral($user_id, $integral, $title);
    }


}