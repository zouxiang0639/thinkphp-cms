<?php
namespace app\user\controller;


use app\common\bls\cart\CartBls;
use app\common\bls\cart\traits\CartTrait;
use app\common\bls\goods\GoodsSubProductBls;
use app\common\bls\order\OrderAttachmentBls;
use app\common\bls\order\OrderBls;
use app\common\bls\order\traits\OrderTrait;
use app\common\consts\order\OrderStatusConst;
use app\common\library\format\FormatOrder;
use app\common\library\sdk\AliPaySdk;
use think\Db;

class Buy extends BasicController
{
    use CartTrait, OrderTrait;


    public function confirm()
    {
        $param = $this->request->param();
        $id = array_get($param, 'id');

        $where['user_id'] = $this->user_id;

        if($this->request->isGet()){
            $where['goods_id'] = $id;
        } else {
            $where['cart_id'] = ['in', $id];
        }
        $model = CartBls::getGoodsSelect($where);

        if($model->isEmpty()) {
            return abort(404,'没有页面');
        }
        $this->formatCart($model);
        return $this->fetch('' , [
            'list' => $model
        ]);
    }

    public function generateOrder()
    {
        if($this->request->isPost()) {
            $post = $this->request->post();
            $payType = intval($post['buy_type']);

            if(empty($payType)) {
                return $this->error('请选择支付平台');
            }

            $model = CartBls::getGoodsSelect(['cart_id' => ['in', $post['id']]]);
            if($model->isEmpty()){
                return $this->error('购物车是已空!');
            }

            $sn = FormatOrder::getOrderId();
            $result = Db::transaction(function() use ($model, $sn, $payType) {
                $delete = false;
                $cartId = [];
                foreach($model as $value){
                    $cartId[] = $value->cart_id;
                    $data = [
                        'sn' => $sn,
                        'status' => OrderStatusConst::PAYING,
                        'amount' => $value->amount,
                        'user_id' => $this->user_id,
                        'pay_type' => $payType,
                        'goods_id' => $value->goods_id,
                        'goods_name' => $value->goods_name,
                    ];
                    $order = orderBls::createOrder($data);

                    $subProduct = self::GoodsSubProduct($value->goods_subproduct_id, $order->order_id);
                    OrderAttachmentBls::createAllOrderAttachment($subProduct);

                    $delete =  $value->delete();
                }

                return $delete;
            });

            if($result) {
                return $this->success('订单生成成功', url('payment', ['sn' => $sn,'type' => $payType]));
            } else {
                return $this->error('订单生成失败');
            }
        }
        return abort(404, '没有这个页面');
    }

    public function payment()
    {
        $sn = input('sn');
        $model = OrderBls::getOrderSelect([
            'sn' => $sn,
            'user_id' => $this->user_id
        ]);
        $this->formatOrder($model);
        $data = [
            "order_no" => $sn,
            "amount" => ($model->amount/100),// 单位为元 ,最小为0.01
            //"amount" => 0.01,// 单位为元 ,最小为0.01
            //"client_ip" => '127.0.0.1',
            "subject" => '新余五月信息单号' . $sn,
            "body" => '',
            "extra_param" => ['sn' => $sn],
        ];

        $payUrl = (new AliPaySdk)->aliPayWeb($data);

        return $this->fetch('' , [
            'list' => $model,
            'payUrl' => $payUrl
        ]);
    }

    private function GoodsSubProduct($subProductId, $orderId)
    {
        $array = [];
        $product = GoodsSubProductBls::getSubProductSelect([
            'goods_subproduct_id' => ['in', $subProductId]
        ]);

        foreach($product as $item) {
            $array[] = [
                'order_id' => $orderId,
                'goods_subproduct_id' => $item->goods_subproduct_id,
                'goods_id' => $item->goods_id,
                'file' => $item->extend['file'],
                'price' => $item->price,
                'title' => $item->title
            ];
        }
        return $array;
    }
}