<?php
namespace app\common\library\integral;
use app\common\bls\user\model\UserModel;
use app\common\bls\user\UserBls;
use app\common\consts\integral\IntegralLevelConst;


/**
 * 积分规则
 */
class Rule
{
    /**
     * 增加积分
     * @param $integral
     * @param $model
     * @return array|bool|false|\PDOStatement|string|\think\Model
     */
    public function add($integral, UserModel $model)
    {
        $model->integral = $model->integral + $integral;
        $model->total_integral = $model->total_integral + $integral;
        $model->level = self::diffLevel($model->level, $model->total_integral);
        if($model->save()) {
            return $model;
        }

        return false;
    }


    /**
     * 减积分
     * @param $integral
     * @param $user_id
     * @return array|bool|false|\PDOStatement|string|\think\Model
     */
    public function reduce($integral, $user_id)
    {
        $model = UserBls::getOneUser(['user_id' => $user_id]);
        $model->integral = $model->integral - $integral;
        if($model->integral >= 0){
            if($model->save()) {
                return $model;
            }
        }
        return false;
    }

    /**
     * 会员等级
     * @param int $level   会员等级
     * @param int $integral  总积分
     * @return int|string
     */
    private function diffLevel($level, $integral)
    {
        if($level != IntegralLevelConst::THREE) {
            $checkLevel = IntegralLevelConst::checkLevel($integral);
            if($checkLevel > $level) {
                return $checkLevel;
            }
        }

        return $level;
    }
}