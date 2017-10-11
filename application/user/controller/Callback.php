<?php
namespace app\user\controller;

use app\common\library\sdk\AliPaySdk;

class Callback
{
    public function alipayCallback()
    {
        return (new AliPaySdk())->aliPayCallback();
    }
}