<?php
namespace app\user\controller;

use app\common\bls\cart\CartBls;
use app\common\bls\cart\traits\CartTrait;
use app\common\bls\goods\GoodsBls;
use app\common\bls\goods\GoodsSubProductBls;

class Cart extends BasicController
{
    use CartTrait;

    public function index()
    {
        $model = CartBls::getCartList(['user_id' => $this->user_id], 100);
        $this->formatCart($model->getCollection());
        return $this->fetch('' , [
            'list' => $model
        ]);
    }

    public function create()
    {
        if($this->request->isPost()) {
            $data = [];
            $amount = 0;
            $post = $this->request->post();

            //数据验证
            $result = $this->validate($post, 'app\common\bls\cart\validate\CartValidate.basicEdit');
            if(true !== $result){
                // 验证失败 输出错误信息
                return $this->error($result);
            }

            $goods = GoodsBls::getOneGoods(['goods_id' => $post['goods_id']]);
            $cart = CartBls::getOneCart(['goods_id' => $post['goods_id'], 'user_id' => $this->user_id]);
            $product = GoodsSubProductBls::getSubProductSelect(['goods_subproduct_id' => ['in', $post['product_id']]]);

            foreach ($product as $value) {
                $amount += $value->price;
            }

            if($goods) {
                $data = [
                    'user_id' => $this->user_id,
                    'goods_id' => $goods->goods_id,
                    'goods_name'=> $goods->title,
                    'goods_subproduct_id' => $post['product_id'],
                    'amount' => $amount
                ];
            }

            if(CartBls::createCart($cart, $data)) {
                return $this->success('已加入购物车');
            } else {
                return $this->success('产品已存在');
            }

        }
    }

    public function delete()
    {
        if($this->request->isDelete()) {
            $post = $this->request->post();
            $model = CartBls::getOneCart(['cart_id' => $post['id']]);

            if(empty($model)){
                return $this->error('参数错误');
            }

            if($model->delete()) {
                return $this->success('删除成功');
            } else {
                return $this->error('删除失败');
            }
        }
    }

}