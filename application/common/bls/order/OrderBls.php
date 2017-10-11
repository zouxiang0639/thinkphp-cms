<?php
namespace app\common\bls\order;

use app\common\bls\order\model\OrderModel;

class OrderBls
{

    public static function getOrderList($where = '', $limit = 20, $order = 'order_id desc')
    {
        return OrderModel::where($where)->order($order)->paginate($limit, '', [
            'query' => input()
        ]);
    }

    /**
     * @param string $where
     * @return OrderModel
     */
    public static function getOneOrder($where = '')
    {
        return OrderModel::where($where)->find();
    }

    public static function createOrder($data)
    {
        return OrderModel::create($data);
    }

    public static function getOrderSelect($where, $order = '')
    {
        return OrderModel::where($where)->order($order)->select();
    }

    public static function orderUpdate($where, $status)
    {
        return OrderModel::where($where)->update($status);
    }

    public static function orderCallback($sn, $data)
    {
        $data = [
            'pay_time' => $data['pay_time'],
            'pay_type' => $data['pay_type'],
            'status' => $data['status'],
            'transaction_id' => $data['transaction_id']
        ];
        return OrderModel::where(['sn'=>$sn])->update($data);
    }
}