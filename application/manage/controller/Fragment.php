<?php
namespace app\manage\controller;

use app\common\bls\fragment\FragmentBls;
use app\common\model\FragmentModel;

class Fragment extends BasicController
{
    private $id     = 0;

    public function __construct()
    {
        parent::__construct();
        $this->id       = !empty($this->request->param('id')) ? intval($this->request->param('id')) : $this->id;
        $nav = [
            '碎片列表' => ['url' => 'index'],
            '碎片增加' => ['url' => 'add'],
            '碎片修改' => ['url' => ['edit', ['id' => $this->id]], 'style' => "display: none;"],
        ];
        $this->assign('navTabs',  parent::navTabs($nav));
    }

    public function index()
    {
        FragmentBls::getAllFragment();
        $where = [];
        if(!empty(input('title'))){
            $where['title'] =  ['like', '%'.input('title').'%'];
        }

        $model = FragmentBls::getFragmentList($where);
        return $this->fetch('',[
            'list'      => $model,
        ]);
    }

    /**
     * 碎片添加
     */
    public function add()
    {
        //Add_post 数据处理
        if($this->request->isPost()){
            $post   = $this->request->post();

            //数据验证
            $result = $this->validate($post, 'app\common\bls\fragment\validate\FragmentValidate.create');
            if(true !== $result){
                // 验证失败 输出错误信息
                return $this->error($result);
            }

            //写入数据库
            if(FragmentBls::createFragment($post)){
                return $this->success(lang('Add success'), url('index'));
            }else{
                return $this->error(lang('Add failed'));
            }

        }

        return $this->fetch('fragment', [
        ]);
    }

    /**
     * 碎片修改
     */
    public function edit()
    {

        $model = FragmentBls::getOneFragment(['fragment_id'=>$this->id]);
        if(empty($model)){
            return $this->error('参数错误');
        }
        return $this->fetch('fragment', [
            'info'   => $model
        ]);
    }

    /**
     * 碎片更新
     */
    public function update()
    {

        if($this->request->isPost() && !empty($this->id)){
            $post   = $this->request->post();

            //数据验证
            $result = $this->validate($post, 'app\common\bls\fragment\validate\FragmentValidate.update');
            if(true !== $result){
                // 验证失败 输出错误信息
                return $this->error($result);
            }

            //更新数据库
            $update = FragmentBls::getOneFragment(['fragment_id'=>$this->id]);
            if(empty($update)){
                return $this->error('参数错误');
            }
            if($update->save($post)){
                return $this->success(lang('Update success'), url('index'));
            }else{
                return $this->error(lang('Update failed'));
            }
        }
        return $this->error('请求错误');
    }

    /**
     * 碎片删除
     */
    public function delete()
    {
        if($this->request->isPost() && !empty($this->id)){
            $delete  = FragmentBls::getOneFragment(['fragment_id'=>$this->id]);
            if(empty($delete)){
                return $this->error('参数错误');
            }
            if($delete->delete()){
                return $this->success(lang('delete success'), url('index'));
            }else{
                return $this->error(lang('delete failed'));
            }
        }
        return $this->error('请求错误');
    }

}