<?php
namespace app\manage\controller;

use app\common\bls\page\PageBls;
use app\common\consts\common\CommonStatusConst;
use app\common\consts\page\PageTemplateConst;
use app\common\model\ExtendedModel;
use app\common\tool\Helper;

class page extends BasicController
{
    private $id         = 0;
    private $url        = 'index';
    private $validate   = [
        ['title|标题', 'require'],
    ];

    public function __construct()
    {
        parent::__construct();

        $this->id           = intval(array_get($this->request->param(), 'id'));

        $nav = [
            '页面列表' => ['url' => 'page/index'],
            '页面增加' => ['url' => 'page/add'],
            '页面修改' => ['url' => ['page/edit', ['id' => $this->id]], 'style' => "display: none;"],
        ];
        $this->assign('navTabs',  parent::navTabs($nav));
    }

    public function index()
    {
        $model = PageBls::getPageList();
        return $this->fetch('', [
            'list'      => $model
        ]);
    }

    /**
     * 分类增加
     */
    public function add(){

        if($this->request->isPost()){
            $post   = Helper::recombinantArray($this->request->post(), 'photos');

            //数据验证
            $result = $this->validate($post, $this->validate);
            if($result !== true){
                return $this->error($result);
            }

            //写入数据库
            if(PageBls::createPage($post)){
                return $this->success(lang('Add success'), url($this->url));
            }else{
                return $this->error(lang('Add failed'));
            }
        }


        //扩展数据form生成
        $parentCategory['extendeds']  = ExtendedModel::formBuilder(0);
        return $this->fetch('page', [
            'enum' => self::enum(),
            'info' => $parentCategory
        ]);
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

        //扩展数据form生成
        $info['extendeds'] = ExtendedModel::formBuilder($info['fields_extended_id'], $info->extend);
        return $this->fetch('page',[
            'enum' => self::enum(),
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
            $result = $this->validate($post,$this->validate);
            if($result !== true){
                return $this->error($result);
            }

            //查询数据
            $model  = PageBls::getOnePage(['page_id'=>$this->id]);

            if(!empty($model)){
                //修改数据库
                if($model->save($post)){
                    return $this->success(lang('Update success'),$this->url);
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

        //数据删除
        if($model->delete()){
            return $this->success(lang('Delete success'),url($this->url));
        }else{
            return $this->error(lang('Delete failed'));
        }
    }

    /**
     * 枚举数组
     *
     * @return array
     */
    private function enum()
    {
        $extendedGroup   = ExtendedModel::extendedGroup();
        return  [
            'display'            => CommonStatusConst::desc(),
            'template_group'     => PageTemplateConst::groupDesc(),
            'fields_extended'    => $extendedGroup[1],  //字段扩展
            'data_extended'      => $extendedGroup[0],  //所有的扩展
            'template_default'   => PageTemplateConst::pageDesc(),
            'template_info'      => PageTemplateConst::infoDesc(),
        ];
    }

}