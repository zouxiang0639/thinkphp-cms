<?php
namespace app\common\bls\integral;

use app\common\bls\integral\model\IntegralLogModel;


class IntegralLogBls
{
    public static function getIntegralLogList($where = '', $limit = 20)
    {
        return IntegralLogModel::where($where)
            ->order(['integral_rule_id' => 'desc'])
            ->paginate($limit, '', [
                'query' => input()
            ]);
    }

    public static function createIntegralLog($date)
    {
        $model = new IntegralLogModel();
        $model->title       = $date['title'];
        $model->integral_rule_id = $date['integral_rule_id'];
        $model->user_id     = $date['user_id'];
        $model->integral    = $date['integral'];
        $model->type        = $date['type'];
        return $model->save();
    }

    public static function getOneIntegralLog($where)
    {
        return IntegralLogModel::where($where)->find();
    }

    public static function getIntegralLogSelect($where, $order = '')
    {
        return IntegralLogModel::where($where)->order($order)->select();
    }
}