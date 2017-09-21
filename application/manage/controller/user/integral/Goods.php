<?php
namespace app\manage\controller\user\integral;

use app\common\bls\integral\IntegralGoodsBls;
use app\common\bls\integral\IntegralRuleBls;
use app\common\bls\integral\traits\IntegralGoodsTrait;
use app\common\consts\common\CommonSwitchConst;
use app\manage\controller\BasicController;

class Goods extends BasicController
{
    use IntegralGoodsTrait;

    private $id     = 0;

    public function __construct()
    {
        parent::__construct();
        $this->id       = !empty($this->request->param('id')) ? intval($this->request->param('id')) : $this->id;
        $nav = [
            '积分商品列表' => ['url' => 'index'],
            '积分商品增加' => ['url' => 'add'],
            '积分商品修改' => ['url' => ['edit', ['id' => $this->id]], 'style' => "display: none;"],
        ];
        $this->assign('navTabs',  parent::navTabs($nav));
    }

    public function index()
    {
        $where  = [];
        if(!empty($this->id)){
            $where['integral_goods_id']  = $this->id;
        }

        $model = IntegralGoodsBls::getIntegralGoodsList($where);
        $this->formatIntegralGoods($model->getCollection());

        return $this->fetch('',[
            'list'      => $model,
        ]);
    }

    /**
     * 积分商品添加
     */
    public function add()
    {
        //Add_post 数据处理
        if($this->request->isPost()){
            $post   = $this->request->post();

            $result = $this->validate($post, 'app\common\bls\integral\validate\IntegralGoodsValidate.create');
            if(true !== $result){
                // 验证失败 输出错误信息
                return $this->error($result);
            }

            //写入数据库
            if(IntegralGoodsBls::createIntegralGoods($post)){
                return $this->success('添加成功', url('index'));
            }else{
                return $this->error('添加失败');
            }

        }

        return $this->fetch('goods');
    }

    /**
     * 积分商品修改
     */
    public function edit()
    {

        $model = IntegralGoodsBls::getOneIntegralGoods(['integral_goods_id' => $this->id]);
        if(empty($model)){
            return $this->error('参数错误');
        }
        return $this->fetch('goods',[
            'info'   => $model
        ]);
    }

    /**
     * 积分商品更新
     */
    public function update()
    {

        if($this->request->isPost() && !empty($this->id)){
            $post   = $this->request->post();

            //数据验证
            $result = $this->validate($post, 'app\common\bls\integral\validate\IntegralGoodsValidate.update');
            if(true !== $result){
                // 验证失败 输出错误信息
                return $this->error($result);
            }

            //更新数据库
            $model = IntegralGoodsBls::getOneIntegralGoods(['integral_goods_id' => $this->id]);
            if(empty($model)){
                return $this->error('参数错误');
            }

            if(IntegralGoodsBls::IntegralGoodsUpdate($model, $post)){
                return $this->success('更新成功', url('index'));
            }else{
                return $this->error('更新失败');
            }
        }

        return $this->error('参数错误');
    }

    /**
     * 积分商品删除
     */
    public function delete()
    {
        if($this->request->isPost() && !empty($this->id)){
            $model = IntegralGoodsBls::getOneIntegralGoods(['integral_goods_id' => $this->id]);
            if(empty($model)){
                return $this->error('参数错误');
            }

            if($model->status == CommonSwitchConst::ON) {
                return $this->error('请关闭后在删除');
            }

            if($model->delete()){
                return $this->success('删除成功', url('index'));
            }else{
                return $this->error('删除失败');
            }
        }
        return $this->error('参数错误');
    }

}