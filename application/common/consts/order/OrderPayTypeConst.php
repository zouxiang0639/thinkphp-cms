<?php
namespace app\common\consts\order;

/**
 * 支付类型
 */
class OrderPayTypeConst
{
    const ALI = 1;
    const WX = 2;


    const ALI_DESC = '支付宝';
    const WX_DESC = '微信支付';

    const ALI_IMAGE = '/user/image/alipay.png';
    const WX_IMAGE = '/user/image/weixinpay.png';


    public static function desc()
    {
        return [
            self::ALI => self::ALI_DESC,
            self::WX => self::WX_DESC
        ];
    }

    public static function image()
    {
        return [
            self::ALI => self::ALI_IMAGE,
            self::WX => self::WX_IMAGE
        ];
    }

    public static function getDesc($item)
    {
        return array_get(self::desc(), $item);
    }
}