<?php
namespace app\common\library\integral;
use app\common\bls\user\UserBls;


/**
 * 积分规则
 */
class Rule
{
    public function add($integral, $user_id)
    {
        $model = UserBls::getOneUser(['user_id' => $user_id]);
        $model->integral = $model->integral + $integral;

        if($model->save()) {
            return $model;
        }

        return false;
    }
}