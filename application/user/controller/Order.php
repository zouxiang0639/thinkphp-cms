<?php
namespace app\user\controller;

use app\common\bls\order\OrderBls;
use app\common\bls\order\traits\OrderTrait;
use app\common\consts\order\OrderStatusConst;

class Order extends BasicController
{
    use OrderTrait;

    public function index()
    {
        if(empty($_GET['type'])) {
            $_GET['type'] = OrderStatusConst::PAYING;
        }

        $where['status'] = ['in', input('type')];

        $model = OrderBls::getOrderList($where, 20);
        if(!$model->isEmpty()){
            $this->formatOrder($model->getCollection());
        }

        return $this->fetch('' , [
            'list' => $model
        ]);
    }

    public function cancel()
    {
        if($this->request->isDelete()) {
            $post = $this->request->post();
            $model = OrderBls::getOneOrder([
                'order_id' => $post['id'],
                'user_id' => $this->user_id
            ]);

            if(empty($model)){
                return $this->error('参数错误');
            }
            $model->status = OrderStatusConst::ORDER_CANCLE;
            if($model->save()) {
                return $this->success('取消成功');
            } else {
                return $this->error('取消失败');
            }
        }
    }
}