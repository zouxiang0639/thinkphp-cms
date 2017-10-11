<?php
namespace app\common\library\sdk;

use app\common\bls\order\OrderBls;
use app\common\consts\order\OrderPayTypeConst;
use app\common\consts\order\OrderStatusConst;
use Payment\Notify\PayNotifyInterface;
use Payment\Config;

/**
 * 客户端需要继承该接口，并实现这个方法，在其中实现对应的业务逻辑
 * Class PayNotifySdk
 */
class PayNotifySdk implements PayNotifyInterface
{
    public function notifyProcess(array $data)
    {
        $channel = $data['channel'];
        if ($channel === Config::ALI_CHARGE) {// 支付宝支付
            $update = [
                'pay_time' => $data['gmt_payment'],
                'pay_type' => OrderPayTypeConst::ALI,
                'status' => OrderStatusConst::FINISH,
                'transaction_id' => $data['trade_no']
            ];

            return OrderBls::orderCallback($data['out_trade_no'], $update);

        } elseif ($channel === Config::WX_CHARGE) {// 微信支付

        } elseif ($channel === Config::CMB_CHARGE) {// 招商支付

        } elseif ($channel === Config::CMB_BIND) {// 招商签约

        } else {
            // 其它类型的通知
        }

        // 执行业务逻辑，成功后返回true
        return true;
    }
}
