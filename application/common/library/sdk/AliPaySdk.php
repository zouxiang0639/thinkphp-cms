<?php
namespace app\common\library\sdk;


use Payment\Client\Charge;
use Payment\Client\Notify;
use Payment\Common\PayException;
use Payment\Config;

/**
 * 如果需要使用这个sdk请先安装
 * composer require "riverslei/payment:~4.1.3"
 *
 * Class AliPaySdk
 */
class AliPaySdk
{
    public  $config = [];

    public function __construct()
    {
        $this->config = config('sdk.aliPay');
    }

    public function aliPayWeb($data)
    {
        try {

            return Charge::run(Config::ALI_CHANNEL_WEB,  $this->config, $data);
        } catch (PayException $e) {
            echo $e->errorMessage();
            exit;
        }
    }

    public function aliPayCallback()
    {
        //$_POST = self::testData();
        $callback = new PayNotifySdk();
        $type = 'ali_charge';// ali_charge wx_charge  cmb_charge

        try {
            // 获取第三方的原始数据，未进行签名检查，根据自己需要决定是否需要该步骤
            //$retData = Notify::getNotifyData($type, $this->config);
            $ret = Notify::run($type, $this->config, $callback);// 处理回调，内部进行了签名检查
        } catch (PayException $e) {
            echo $e->errorMessage();
            exit;
        }
        return $ret;
    }

    public function testData()
    {
       return array (
           'gmt_create' => '2017-10-11 15:19:46',
           'charset' => 'UTF-8',
           'gmt_payment' => '2017-10-11 15:19:50',
           'notify_time' => '2017-10-11 15:19:51',
           'subject' => '新余五月信息单号2017101159ddc5397c42e',
           'sign' => 'I7GXvVjPZldk4N17TQFLa1qxAe0ob662sUteKz9/+iuxOQy3H74uBMf1+chb5nGWFvXb+jqZWX6XdSQqXv40s5XhAg3A9fcCs35ta3rqLSTbI75UhRNsMtd9A66RHG1AYjf2IMUh1R0eQAFlfZb+wpumWmM168YOoGs7i6wT8CJ3ZUxzok7B3i6O1LelknAssGouf9Yiem3B7GGeRhCZVLxcdAlm6Thc1nSaLpw4SXmCRFNL5oeFBVaX/eXLHvklq+piu23266rwX3VhiPQxjIbwvdmbxZd+46Tmc5qM1qG1kahaoxInCffkpsweHL3XYqyq1dCvpyw643lHBJw2Mg==',
           'buyer_id' => '2088702467020514',
           'body' => '新余五月信息',
           'invoice_amount' => '0.01',
           'version' => '1.0',
           'notify_id' => 'ba963959f3e438b9e41227b0287703fjxq',
           'fund_bill_list' => '[{"amount":"0.01","fundChannel":"ALIPAYACCOUNT"}]',
           'notify_type' => 'trade_status_sync',
           'out_trade_no' => '2017101159ddc5397c42e',
           'total_amount' => '0.01',
           'trade_status' => 'TRADE_SUCCESS',
           'trade_no' => '2017101121001004510287099563',
           'auth_app_id' => '2017091608767406',
           'receipt_amount' => '0.01',
           'point_amount' => '0.00',
           'app_id' => '2017091608767406',
           'buyer_pay_amount' => '0.01',
           'sign_type' => 'RSA2',
           'seller_id' => '2088521041471553',
       );
    }
}