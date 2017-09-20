<?php
namespace app\common\bls\integral;

use app\common\bls\integral\model\IntegralRuleModel;
use app\common\bls\page\PageBls;
use app\common\consts\extended\ExtendedTypeConst;
use think\Db;

class IntegralRuleBls
{
    public static function getIntegralRuleList($where = '', $limit = 20)
    {
        return IntegralRuleModel::where($where)
            ->order(['integral_rule_id' => 'desc'])
            ->paginate($limit, '', [
                'query' => input()
            ]);
    }

    public static function createIntegralRule($date)
    {
        $model = new IntegralRuleModel();
        $model->title       = $date['title'];
        $model->rule_method = $date['rule_method'];
        $model->integral    = $date['integral'];
        $model->status      = $date['status'];
        return $model->save();
    }

    public static function IntegralRuleUpdate(IntegralRuleModel $model, $date)
    {
        $model->title       = $date['title'];
        $model->integral    = $date['integral'];
        $model->status      = $date['status'];
        return $model->save();
    }


    public static function getOneIntegralRule($where)
    {
        return IntegralRuleModel::where($where)->find();
    }

    public static function getIntegralRuleSelect($where, $order = '')
    {
        return IntegralRuleModel::where($where)->order($order)->select();
    }
}