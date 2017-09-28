<?php
namespace app\common\library\integral;

use app\common\bls\integral\IntegralLogBls;
use app\common\bls\integral\IntegralRuleBls;
use app\common\bls\user\UserBls;
use app\common\consts\common\CommonSwitchConst;
use app\common\consts\integral\IntegralLevelConst;
use app\common\consts\integral\IntegralTypeConst;
use think\Config;

/**
 * 积分规则处理
 */
class IntegralRule
{
    protected $rights = false;

    public function __construct()
    {
        $config = Config::get('extend.integral_library');
        if(!empty($config)) {
            $this->rights = array_get($config, 'rights', $this->rights);
        }
    }

    /**
     * 检查积分规则
     * @param $id
     * @param $userId
     * @return bool|string
     */
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

        //根据积分规则的处理方法 调用Rule对象方法
        if(method_exists($object, $model->rule_method)){

            $userModel = UserBls::getOneUser(['user_id' => $userId]);

            //积分等级权益
            if($this->rights) {
                $rights = IntegralLevelConst::getRights($userModel->level);
                $model->integral = intval($rights * $model->integral);
            }

            $result =  call_user_func([$object, $model->rule_method], $model->integral, $userModel);



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

    /**
     * 创建积分日志
     * @param $data
     * @return false|int
     */
    public function createLog($data)
    {
        return IntegralLogBls::createIntegralLog($data);
    }

    /**
     * 减积分
     * @param $userId
     * @param $integral
     * @param $title
     * @return bool|string
     */
    public function reduceIntegral($userId, $integral, $title)
    {

        $rule = new Rule();

        $result = $rule->reduce($integral, $userId);
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

    public function manuallyAddIntegral($userId, $integral, $title)
    {
        $userModel = UserBls::getOneUser(['user_id' => $userId]);

        //积分等级权益
        if($this->rights) {
            $rights = IntegralLevelConst::getRights($userModel->level);
            $integral = intval($rights * $integral);
        }

        $rule = new Rule();
        $result = $rule->add($integral, $userModel);
        if($result) {
            $data = [
                'integral_rule_id' => 0,
                'title' => $title,
                'user_id' => $userId,
                'integral' => $integral,
                'type' => IntegralTypeConst::ADD
            ];
            self::createLog($data);
            return true;
        }

        return false;
    }
}