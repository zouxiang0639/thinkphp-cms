<?php
namespace app\common\library\integral;

use app\common\bls\integral\IntegralLogBls;
use app\common\bls\integral\IntegralRuleBls;
use app\common\consts\common\CommonSwitchConst;
use app\common\consts\integral\IntegralTypeConst;

/**
 * 积分规则处理
 */
class IntegralRule
{
    public function checkIntegralRule ($id, $user_id)
    {
        $model = IntegralRuleBls::getOneIntegralRule(['integral_rule_id' => $id]);
        if(empty($model)){
            return '参数错误';
        }

        if($model->status == CommonSwitchConst::OFF) {
            return '积分规则已关闭';
        }

        $object = new Rule();

        if(method_exists($object, $model->rule_method)){
            $result =  call_user_func([$object, $model->rule_method], $model->integral, $user_id);

            if($result) {
                self::createLog($result, $model);
                return true;
            }

            return '积分添加失败';
        }
    }

    public function createLog($user, $integralRule)
    {
        $data = [
            'integral_rule_id' => $integralRule->integral_rule_id,
            'title' => $integralRule->title,
            'user_id' => $user->user_id,
            'integral' => $integralRule->integral,
            'type' => IntegralTypeConst::ADD
        ];

        return IntegralLogBls::createIntegralLog($data);
    }
}