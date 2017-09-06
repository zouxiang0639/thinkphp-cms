<?php
namespace app\manage\controller;


use app\common\bls\label\LabelBls;
use app\common\bls\label\traits\LabelTrait;

class Label extends BasicController
{
    use LabelTrait;
    private $id     = 0;

    public function __construct()
    {
        parent::__construct();
        $this->id       = !empty($this->request->param('id')) ? intval($this->request->param('id')) : $this->id;
        $nav = [
            '标签列表' => ['url' => 'index'],
            '标签增加' => ['url' => 'add'],
            '标签修改' => ['url' => ['edit', ['id' => $this->id]], 'style' => "display: none;"],
        ];
        $this->assign('navTabs',  parent::navTabs($nav));
    }

    public function index()
    {

        $where  = [];

        $type   = $this->request->get('type');
        if(!empty($type)){
            $where['type']  = $type;
        }

        $title   = $this->request->get('title');
        if(!empty($title)){
            $where['title']  = ['like',"%{$title}%"];;
        }

        $model = LabelBls::getLabelList($where);
        $this->formatLabel($model->getCollection());
        return $this->fetch('',[
            'list'      => $model,
        ]);
    }

    /**
     * 标签添加
     */
    public function add()
    {
        //Add_post 数据处理
        if($this->request->isPost()){
            $post   = $this->request->post();

            $result = $this->validate($post, 'app\common\bls\label\validate\LabelValidate.create');
            if(true !== $result){
                // 验证失败 输出错误信息
                return $this->error($result);
            }
            //写入数据库
            if(LabelBls::createLabel($post)){
                return $this->success(lang('Add success'), url('index'));
            }else{
                return $this->error(lang('Add failed'));
            }

        }

        return $this->fetch('label');
    }

    /**
     * 标签修改
     */
    public function edit()
    {

        $model = LabelBls::getOneLabel(['label_id'=>$this->id]);
        if(empty($model)){
            return $this->error('参数错误');
        }
        return $this->fetch('label',[
            'info'   => $model
        ]);
    }

    /**
     * 标签更新
     */
    public function update()
    {

        if($this->request->isPost() && !empty($this->id)){
            $post   = $this->request->post();

            //数据验证
            $result = $this->validate($post, 'app\common\bls\label\validate\LabelValidate.update');
            if(true !== $result){
                // 验证失败 输出错误信息
                return $this->error($result);
            }

            //更新数据库
            $update = LabelBls::getOneLabel(['label_id'=>$this->id]);
            if(empty($update)){
                return $this->error('参数错误');
            }

            if($update->save($post)){
                return $this->success(lang('Update success'), url('index'));
            }else{
                return $this->error(lang('Update failed'));
            }
        }

        return $this->error('参数错误');
    }

    /**
     * 标签删除
     */
    public function delete()
    {
        if($this->request->isPost() && !empty($this->id)){
            $model  = LabelBls::getOneLabel(['label_id'=>$this->id]);
            if(empty($model)){
                return $this->error('参数错误');
            }
            if($model->delete()){
                return $this->success(lang('delete success'), url('index'));
            }else{
                return $this->error(lang('delete failed'));
            }
        }
        return $this->error('参数错误');
    }

}