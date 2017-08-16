<?php
namespace app\manage\controller;

use app\common\bls\extended\ExtendedBls;
use app\common\bls\page\PageBls;
use app\common\consts\common\CommonStatusConst;
use app\common\consts\page\PageTemplateConst;
use app\common\tool\Helper;
use app\common\bls\page\traits\PageTrait;

class page extends BasicController
{
    use PageTrait;

    private $id         = 0;

    public function __construct()
    {
        parent::__construct();

        $this->id           = input('id');

        $nav = [
            '页面列表' => ['url' => 'index'],
            '页面增加' => ['url' => 'add'],
            '页面修改' => ['url' => ['edit', ['id' => $this->id]], 'style' => "display: none;"],
        ];
        $this->assign('navTabs',  parent::navTabs($nav));
    }

    public function index()
    {
        $param = $this->request->param();
        $where = [];
        if(!empty($param['type'])) {
            $where['template_type'] = $param['type'];
        }

        if(!empty($param['title'])) {
            $where['title'] = ['like',"%{$param['title']}%"];
        }

        $model = PageBls::getPageList($where);
        $this->formatPage($model->getCollection());
        return $this->fetch('', [
            'list'      => $model
        ]);
    }

    /**
     * 分类增加
     */
    public function add(){

        //扩展数据form生成
        $extendedGroup   = ExtendedBls::extendedGroup();
        $enum =  [
            'display'            => CommonStatusConst::desc(),
            'fields_extended'    => $extendedGroup[1],  //字段扩展
            'data_extended'      => $extendedGroup[0],  //所有的扩展
        ];

        return $this->fetch('page', [
            'enum' => $enum,
        ]);
    }

    public function create()
    {
        if($this->request->isPost()){
            $post   = Helper::recombinantArray($this->request->post(), 'photos');

            //数据验证
            $result = $this->validate($post, 'app\common\bls\page\validate\pageValidate.create');
            if(true !== $result){
                // 验证失败 输出错误信息
                return $this->error($result);
            }

            //写入数据库
            if(PageBls::createPage($post)){
                return $this->success(lang('Add success'), url('index'));
            }else{
                return $this->error(lang('Add failed'));
            }
        }
    }

    /**
     * 分类修改
     */
    public function edit()
    {
        $info = PageBls::getOnePage(['page_id'=>$this->id]);
        if(empty($info)){
            return $this->error('参数错误');
        }

        $extendedGroup   = ExtendedBls::extendedGroup();
        $enum =  [
            'fields_extended'    => $extendedGroup[1],  //字段扩展
            'data_extended'      => $extendedGroup[0],  //所有的扩展
        ];

        return $this->fetch('page',[
            'enum' => $enum,
            'info' => $info
        ]);
    }

    /**
     * 分类更新
     */
    public function update(){

        //edit_post 数据处理
        if($this->request->isPost()){

            $post   = Helper::recombinantArray($this->request->post(), 'photos');
            //数据验证
            $result = $this->validate($post, 'app\common\bls\page\validate\pageValidate.update');
            if(true !== $result){
                // 验证失败 输出错误信息
                return $this->error($result);
            }

            //查询数据
            $model  = PageBls::getOnePage(['page_id'=>$this->id]);

            if(!empty($model)){
                //修改数据库
                if($model->save($post)){
                    return $this->success(lang('Update success'), url('index'));
                }else{
                    return $this->error(lang('Update failed'));
                }
            }

        }

        return $this->error('参数错误');
    }

    /**
     * 分类删除
     */
    public function delete()
    {
        $model  = PageBls::getOnePage(['page_id'=>$this->id]);

        if(!$this->request->isPost() || empty($model)){
            return $this->error('参数错误');
        }

        if($model->template_type == PageTemplateConst::INFO[0] && $model->info->count()) {
            return $this->error('信息数据请清空');
        }

        if($model->template_type == PageTemplateConst::GOODS[0] && $model->goods->count()) {
            return $this->error('产品数据请清空');
        }

        //数据删除
        if($model->delete()){
            return $this->success(lang('Delete success'));
        }else{
            return $this->error(lang('Delete failed'));
        }
    }

    /**
     *   数据扩展
     */
    public function extended()
    {
        if($this->request->isPost()){
            $page_id    = input('page_id');
            $fields_extended_id    = input('fields_extended_id');
            $data       = [];

            if(!empty($page_id)){
                $page  = PageBls::getOnePage(['page_id'=>$page_id]);
                $data = $page->extend;
            }

            $html = ExtendedBls::formBuilder($fields_extended_id, $data);
            return $this->success('' ,'', $html);
        }
    }

}