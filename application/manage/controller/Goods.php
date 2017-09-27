<?php
namespace app\manage\controller;

use app\common\bls\extended\ExtendedBls;
use app\common\bls\goods\GoodsBls;
use app\common\bls\goods\GoodsSubProductBls;
use app\common\bls\page\PageBls;
use app\common\consts\common\CommonStatusConst;
use app\common\consts\extended\ExtendedTypeConst;
use app\common\consts\page\PageTemplateConst;
use app\common\library\format\FormatData;
use app\common\library\office\Excel;
use app\common\tool\Helper;
use app\common\bls\goods\traits\GoodsTrait;
use think\Config;
use think\model\Collection;

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
            '附属产品' => ['url' => ['subproduct', ['id' => $this->id, 'cid' => $this->cid]], 'style' => "display: none;"],
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
     * 产品增加
     */
    public function add()
    {

        return $this->fetch('goods',[
            'page'              => PageBls::getAllPage(['template_type' => PageTemplateConst::GOODS[0]]),
            'display'           => CommonStatusConst::desc()
        ]);
    }

    /**
     * 产品创建
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
     * 产品修改
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
     * 产品更新
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
     * 产品删除
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
     * 产品排序
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
     * 数据扩展
     */
    public function extended()
    {
        if($this->request->isPost()){

            $type       = input('type');
            $html       = '';
            $data       = [];

            if($type == 'product'){
                $extendedId = input('extended_id');
                $productId = input('product_id');
                if(!empty($productId)){
                    $mode = GoodsSubProductBls::getOneSubProduct(['goods_subproduct_id' => $productId]);
                    $data = $mode->extend;
                }

                $html = ExtendedBls::formBuilder($extendedId, $data);
            } else {
                $page_id    = input('page_id');
                $goods_id   = input('goods_id');
                $page       = PageBls::getOnePage(['page_id'=>$page_id]);
                if(!empty($page) && $extended = $page->extendedData){
                    if(!empty($goods_id)) {
                        $goods = GoodsBls::getOneGoods(['goods_id'=>$goods_id]);
                        if($extended->type == ExtendedTypeConst::FIELD) {
                            $data = $goods->extend;
                        } else {
                            $data = GoodsBls::getGoodsMysqlExtended($extended->name, $goods->goods_id);
                        }
                    }
                    $html = ExtendedBls::formBuilder($page->data_extended_id, $data);
                }
            }


            return $this->success('' ,'', $html);
        }
    }

    /**
     * 导出excel
     */
    public function exportExcel()
    {
        $config = Config::get('excel.goods');
        $field = array(array_keys($config));

        $limit = $this->request->get('limit');

        $model = GoodsBls::getGoodsSelect('', '', $limit);

        $formatData = [];
        foreach($model as $value) {
            $formatData[] = [
                $value->title,
                $value->page_id,
                $value->picture,
                FormatData::photosFormat($value->photos),
                $value->content,
            ];
        }
        $data = array_merge($field, $formatData);

        return Excel::export($data);
    }

    /**
     * 导入excel
     */
    public function loadExcel()
    {
        if($this->request->isPost()) {
            $file = request()->file('file');

            if($file) {

                $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads'. DS . 'excel');
                if($info){
                    $filePath = $info->getPathname();



                    try{
                        //从上传的excel获取数据
                        $field = Config::get('excel.goods');
                        $data = Excel::load($filePath, $field);

                        //格式化数据
                        $data = Collection::make($data);
                        $this->LoadExcelByGoods($data);
                        @unlink($filePath); //删除文件
                        return $this->success('导入成功');

                    } catch (\Exception $e) {
                        @unlink($filePath); //删除文件
                        return $this->success('导入成功');
                    }
                }else{
                    return $this->error($file->getError());
                }
            }
            return $this->error('请上传文件');
        }

        return $this->fetch();
    }

    /****************************************************** 附属产品 ***************************************************/

    /**
     * 附属产品
     */
    public function subproduct()
    {
        $model  = GoodsBls::getOneGoods(['goods_id' => $this->id]);

        return $this->fetch('goods/subproduct/index', [
            'list' => $model->goodsSubProduct,
            'info' => $model
        ]);
    }

    /**
     * 附属添加
     * @return mixed|void
     */
    public function subproductAdd()
    {
        if($this->request->isPost()) {
            $post = $this->request->post();

            //数据验证
            $result = $this->validate($post, 'app\common\bls\goods\validate\GoodsSubProductValidate.create');
            if(true !== $result){
                // 验证失败 输出错误信息
                return $this->error($result);
            }

            if(GoodsSubProductBls::createSubProduct($post)){
                return $this->success(lang('Add success'));
            } else {
                return $this->error(lang('Add failed'));
            }
        }

        return $this->fetch('goods/subproduct/subproduct', [
            'type' => ExtendedBls::getExtendedType(ExtendedTypeConst::SUB_PRODUCT)
        ]);
    }

    /**
     * 附属编辑
     * @return mixed
     */
    public function subproductEdit()
    {
        $mode = GoodsSubProductBls::getOneSubProduct(['goods_subproduct_id' => $this->id]);
        return $this->fetch('goods/subproduct/subproduct', [
            'type' => ExtendedBls::getExtendedType(ExtendedTypeConst::SUB_PRODUCT),
            'info' => $mode
        ]);
    }

    /**
     * 副产品更新
     */
    public function subproductUpdate()
    {
        if($this->request->isPost()){
            $post   = $this->request->post();

            //数据验证
            $result = $this->validate($post, 'app\common\bls\goods\validate\GoodsSubProductValidate.update');
            if(true !== $result){
                // 验证失败 输出错误信息
                return $this->error($result);
            }

            //查询数据
            $model  = GoodsSubProductBls::getOneSubProduct(['goods_subproduct_id' => $this->id]);
            if(empty($model)){
                return $this->error('参数错误');
            }

            //关联数据库扩展模型数据更新
            if(GoodsSubProductBls::goodsSubProductUpdate($model,$post)){
                return $this->success(lang('Update success'));
            }else{
                return $this->error(lang('Update failed'));
            }
        }
    }

    /**
     * 副产品删除
     */
    public function subproductDelete()
    {
        if($this->request->isPost() && !empty($this->id)){
            $model  = GoodsSubProductBls::getOneSubProduct(['goods_subproduct_id' => $this->id]);

            if(empty($model)){
                return $this->error('参数错误');
            }

            //数据删除
            if($model->delete()){
                return $this->success(lang('delete success'));
            }else{
                return $this->error(lang('delete failed'));
            }
        }
        return $this->error('请求错误');
    }
}