<?php
namespace app\common\consts\order;

/**
 * 订单类型
 */
class OrderStatusConst
{
    const PAYING = 1;
    const PAIED = 2;
    const FINISH = 3;
    const ORDER_CANCLE = 4;
    const OVERDUE = 5;

    const PAYING_DESC = '待支付';
    const PAIED_DESC = '已支付';
    const FINISH_DESC = '订单完成';
    const ORDER_CANCLE_DESC = '订单取消';
    const OVERDUE_DESC = '订单过期';
    const FAIL_DESC = '失败订单';


    public static function desc()
    {
        return [
            self::PAYING => self::PAYING_DESC,
            self::PAIED => self::PAIED_DESC,
            self::FINISH => self::FINISH_DESC,
            self::ORDER_CANCLE => self::ORDER_CANCLE_DESC,
            self::OVERDUE => self::OVERDUE_DESC,
        ];
    }

    public static function orderMenu()
    {
        return [
            self::PAYING => self::PAYING_DESC,
            self::FINISH => self::FINISH_DESC,
            self::ORDER_CANCLE.','.self::OVERDUE => self::FAIL_DESC
        ];
    }

    public static function getDesc($item)
    {
        return array_get(self::desc(), $item);
    }
}