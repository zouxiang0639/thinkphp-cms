<?php
namespace app\common\library\weChat;

use think\Cache;
use think\Config;

class WeChatSdk
{
    private $appId;
    private $appsecret;
    public static    $msgObj;
    public static    $accessToken;

    public function __construct()
    {
        $config             = Config::get('sdk.weChat');
        $this->appId        = $config['appId'];
        $this->appsecret    = $config['appsecret'];
    }

    public  function aaa(){
        dump(self::$accessToken);
    }

    /**
     *  获取accessToken
     */
    public function getAccessToken(){
        $accessToken = Cache::get('accessToken');

        if($accessToken){
            self::$accessToken = $accessToken;
        }else{

            $url    = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$this->appId.'&secret='.$this->appsecret;
            $json   = self::curlGet($url);

            $result = json_decode($json,true);

            $accessToken = $result['access_token'];

            if(array_key_exists('errcode',$result) && $result['errcode']!=0){
                return false;
            }
            Cache::set('accessToken',$accessToken,7000);

            self::$accessToken = $accessToken;
        }

    }

    /**
     *  获取openid
     *  self::codeGet() 先调用codeGet 获取code
     */
    public  function openid(){

        $code = $_GET['code'];

        $state = $_GET['state'];

        $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$this->appId.'&secret='.$this->appsecret.'&code='.$code.'&grant_type=authorization_code';


        $joon = self::curlGet($url);
        $data = json_decode($joon,true);

        if(array_key_exists('errcode',$data) && $data['errcode']!=0){

            return $data;
        }
        $url = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$data['access_token'].'&openid='.$data['openid'].'&lang=zh_CN';
        $joon = self::curlGet($url);
        $result = json_decode($joon,true);
        return $result;
    }


    /**
     * 获取code
     * 微信登录跳转接口
     */
    public function codeGet($state='weixing'){
        //snsapi_base
        $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$this->appId.'&redirect_uri='.urlencode(Url('member/wechatLogin',['url'=>input('url')],true,true))
            .'&response_type=code&scope=snsapi_userinfo&state='.$state.'#wechat_redirect';

        header("location:".$url."");
        die;

    }


    /**
     *
     * 创建栏目
     * self::getAccessToken() 需要先获取self::$accessToken
     *
     * @param array $data
     * @return string
     */
    public function createNav($data)
    {

        self::getAccessToken();
        dump($this->accessToken);die;
        /*  $data = [
            'button'=>[
                [
                    'name'      => '关于我们',
                    'sub_button'=> [
                        [
                            'type'  => 'view',
                            'name'  => '米加介绍',
                            'url'   => 'http://wx.megaaa.com/more/channel/id/14.html'

                        ]
                    ]
                ],
                [
                    'type'  => 'view',
                    "name"  => "米加商城",
                    'url'   => "http://wx.megaaa.com"
                ]
            ]
        ];*/

        $json = json_encode($data,JSON_UNESCAPED_UNICODE);
        $url = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token='.self::$accessToken;

        $result = self::curlPost($url,$json);

        return $result;
    }

    /**
     * 模板消息接口
     * @access public
     * @param array     $data['touser']         微信openid
     * @param array     $data['template_id']    信息模版ID
     * @param array     $data['url']            跳转路由
     * @param array     $data['data']           发送信息数据
     * @return mixed
     */
    public function message($data){

        self::getAccessToken();

        $url = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token='.self::$accessToken;
        $template=array(
            'touser'=>$data['touser'],
            'template_id'=>$data['template_id'],
            'url'=>$data['url'],
            'data'=>$data['data']
        );
        $data=json_encode($template,JSON_UNESCAPED_UNICODE);
        $template_id = self::curlPost($url,$data);
        return $template_id;
    }


    /* arr数组参数
       --> protype   产品类型
       --> oid    订单id
       --> price  价格
*/
    /**
     * 微信pay
     * @access public
     * @param string    $protype                产品类型
     * @param string    $order_id               订单id
     * @param string    $pay_amount             价格
     * @return mixed
     */
    public static function wxPay($payarr)
    {


        \think\Loader::import('WxPay', VENDOR_PATH.'plugin/wxpay/', EXT);

        //初始化日志
        /*  $logHandler= new \CLogFileHandler("../logs/".date('Y-m-d').'.log');
          $log = \Log::Init($logHandler, 15);*/

        //初始化WxPayConfig
        new \WxPayConfig(Config::get('secretkey.wechatPay'));

        //①、获取用户openid

        $tools = new \JsApiPay();
        $openId = $tools->GetOpenid();

        //②、统一下单
        $input = new \WxPayUnifiedOrder();
        $input->SetBody($payarr['title']);
        $input->SetAttach($payarr['title']);
        $input->SetOut_trade_no($payarr['order_id']);
        $input->SetTotal_fee($payarr['pay_amount']*100);
        $input->SetTime_start(date("YmdHis"));
        $input->SetTime_expire(date("YmdHis", time() + 600));
        $input->SetGoods_tag($payarr['title']);
        $input->SetNotify_url(Url('pay/wechatNotify',[],true,true));
        $input->SetTrade_type("JSAPI");
        $input->SetOpenid($openId);

        $order = \WxPayApi::unifiedOrder($input);

        //错误打印
        if(isset($order['err_code']) && $order['err_code']=='OUT_TRADE_NO_USED'){
            return ['code'=>0,'msg'=>$order['err_code_des']];
        }

        $jsApiParameters = $tools->GetJsApiParameters($order);
        //获取共享收货地址js函数参数
        //$editAddress = $tools->GetEditAddressParameters();


        $arr=[
            'jsApiParameters'=>$jsApiParameters,
            // 'editAddress'=>$editAddress,
        ];
        return $arr;
    }

    /**
     *  curlGet
     */
    public  function curlGet($url){
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);
        if(!curl_exec($ch)){
            $data = '';
        }else{
            $data = curl_multi_getcontent($ch);
        }
        curl_close($ch);
        return $data;
    }

    /**
     * curlPost
     */
    public  function curlPost($url,$postDate){
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);
        curl_setopt($ch,CURLOPT_TIMEOUT,30);
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$postDate);

        if(!curl_exec($ch)){
            $data = '';
        }else{
            $data = curl_multi_getcontent($ch);
        }
        curl_close($ch);
        return $data;
    }
}