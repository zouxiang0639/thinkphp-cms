<?php
namespace app\common\library\integral;

/**
 * 积分管理
 */
class Integral
{
    public static function addIntegral($id, $user_id)
    {
        return (new IntegralRule())->checkIntegralRule($id, $user_id);
    }
}