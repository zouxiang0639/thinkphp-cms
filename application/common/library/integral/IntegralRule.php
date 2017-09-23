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
    public function checkIntegralRule ($id, $userId)
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
            $result =  call_user_func([$object, $model->rule_method], $model->integral, $userId);

            if($result) {
                $data = [
                    'integral_rule_id' => $model->integral_rule_id,
                    'title' => $model->title,
                    'user_id' => $result->user_id,
                    'integral' => $model->integral,
                    'type' => IntegralTypeConst::ADD
                ];
                self::createLog($data);
                return true;
            }

            return '积分添加失败';
        }
    }

    public function createLog($data)
    {
        return IntegralLogBls::createIntegralLog($data);
    }

    public function reduceIntegral($userId, $integral, $title)
    {

        $object = new Rule();
        if(method_exists($object, 'reduce')){
            $result =  call_user_func([$object, 'reduce'], $integral, $userId);
            if($result) {
                $data = [
                    'integral_rule_id' => 0,
                    'title' => $title,
                    'user_id' => $userId,
                    'integral' => $integral,
                    'type' => IntegralTypeConst::REDUCE
                ];
                self::createLog($data);
                return true;
            }

            return '积分不够';
        }
    }
}