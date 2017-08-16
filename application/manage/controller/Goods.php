<?php
namespace app\manage\controller;

use app\common\bls\extended\ExtendedBls;
use app\common\bls\goods\GoodsBls;
use app\common\bls\page\PageBls;
use app\common\consts\common\CommonStatusConst;
use app\common\consts\extended\ExtendedTypeConst;
use app\common\consts\page\PageTemplateConst;
use app\common\tool\Helper;
use app\common\bls\goods\traits\GoodsTrait;

class Goods extends BasicController
{
    use GoodsTrait;

    private $id;
    private $cid;

    public function __construct()
    {
        parent::__construct();

        $this->id       = $this->request->param('id');
        $this->cid      = $this->request->param('cid');
        $nav = [
            '产品列表' => ['url' => ['index', ['cid' => $this->cid]]],
            '产品增加' => ['url' => ['add', ['cid' => $this->cid]]],
            '产品修改' => ['url' => ['edit', ['id' => $this->id, 'cid' => $this->cid]], 'style' => "display: none;"],
        ];
        $this->assign([
            'navTabs'   => parent::navTabs($nav),
        ]);
    }

    public function index()
    {
        //条件判断
        $param = $this->request->param();

        $where          = [];
        if(!empty($param['cid'])) {
            $where['page_id'] = $param['cid'];
        }

        if(!empty($param['title'])) {
            $where['title'] = ['like',"%{$param['title']}%"];
        }

        $model = GoodsBls::getGoodsList($where);
        $page = PageBls::getAllPage(['template_type' => PageTemplateConst::GOODS[0]]);
        $this->formatGoods($model->getCollection());
        return $this->fetch('',[
            'list'  => $model,
            'page'  => $page
        ]);
    }

    /**
     * 信息增加
     */
    public function add()
    {

        return $this->fetch('goods',[
            'page'              => PageBls::getAllPage(['template_type' => PageTemplateConst::GOODS[0]]),
            'display'           => CommonStatusConst::desc()
        ]);
    }

    /**
     * 信息创建
     */
    public function create()
    {
        if($this->request->isPost()){

            $post   = Helper::recombinantArray($this->request->post(), 'photos');
            //数据验证
            $result = $this->validate($post, 'app\common\bls\goods\validate\GoodsValidate.create');
            if(true !== $result){
                // 验证失败 输出错误信息
                return $this->error($result);
            }

            //关联数据库扩展模型数据更新
            if(GoodsBls::createGoods($post)){
                return $this->success(lang('Add success'), url('index',['cid' => $this->cid]));
            }else{
                return $this->error(lang('Add failed'));
            }
        }
        return $this->error('请求错误');
    }

    /**
     * 信息修改
     */
    public function edit()
    {
        $model = GoodsBls::getOneGoods(['goods_id' => $this->id]);

        if(empty($model)){
            return $this->error('参数错误');
        }
        return $this->fetch('goods', [
            'info'      => $model,
            'page'      => PageBls::getAllPage(['template_type' => PageTemplateConst::GOODS[0]]),
            'display'   => CommonStatusConst::desc()
        ]);
    }

    /**
     * 信息更新
     */
    public function update(){
        if($this->request->isPost()){
            $post   = Helper::recombinantArray($this->request->post(), 'photos');

            //数据验证
            $result = $this->validate($post, 'app\common\bls\goods\validate\GoodsValidate.update');
            if(true !== $result){
                // 验证失败 输出错误信息
                return $this->error($result);
            }

            //查询数据
            $model  = GoodsBls::getOneGoods(['goods_id' => $this->id]);
            if(empty($model)){
                return $this->error('参数错误');
            }

            //关联数据库扩展模型数据更新
            if(GoodsBls::goodsUpdate($model,$post)){
                return $this->success(lang('Update success'), url('index', ['cid' => $this->cid]));
            }else{
                return $this->error(lang('Update failed'));
            }
        }
        return $this->error('请求错误');
    }

    /**
     * 信息删除
     */
    public function delete()
    {
        if($this->request->isPost() && !empty($this->id)){
            $model  = GoodsBls::getOneGoods(['goods_id' => $this->id]);

            if(empty($model)){
                return $this->error('参数错误');
            }

            //关联数据库扩展模型数据删除
            if(GoodsBls::goodsDelete($model)){
                return $this->success(lang('delete success'));
            }else{
                return $this->error(lang('delete failed'));
            }
        }
        return $this->error('请求错误');
    }

    /**
     * 信息排序
     */
    public function sort()
    {
        $sort   = GoodsBls::getOneGoods(['goods_id' => $this->id]);
        $order  = isset($_POST['order']) ? intval($_POST['order']) : 0;
        if(!$this->request->isPost() || empty($sort)){
            return $this->error('参数错误');
        }

        //更新数据
        if($sort->save(['sort' => $order])){
            return $this->success(lang('Sort success'));
        }else{
            return $this->error(lang('Sort failed'));
        }
    }

    /**
     *   数据扩展
     */
    public function extended()
    {
        if($this->request->isPost()){
            $page_id    = input('page_id');
            $goods_id   = input('goods_id');
            $html       = '';
            $date       = [];
            $page       = PageBls::getOnePage(['page_id'=>$page_id]);

            if(!empty($page) && $extended = $page->extendedData){
                if(!empty($goods_id)) {
                    $goods = GoodsBls::getOneGoods(['goods_id'=>$goods_id]);
                    if($extended->type == ExtendedTypeConst::FIELD) {
                        $date = $goods->extend;
                    } else {
                        $date = GoodsBls::getGoodsMysqlExtended($extended->name, $goods->goods_id);
                    }
                }
                $html = ExtendedBls::formBuilder($page->data_extended_id, $date);
            }
            return $this->success('' ,'', $html);
        }
    }
}