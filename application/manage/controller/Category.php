<?php
namespace app\manage\controller;

use app\common\bls\category\CategoryBls;
use app\common\bls\category\traits\CategoryTrait;
use app\common\bls\page\PageBls;
use app\common\library\trees\Tree;


class Category extends BasicController
{

    use CategoryTrait;

    private $id;

    public function __construct()
    {
        parent::__construct();

        $this->id       = intval(array_get($this->request->param(), 'id'));

        $nav = [
            '导航列表' => ['url' => 'index'],
            '导航添加' => ['url' => 'add'],
            '导航修改' => ['url' => ['edit',['id'=> $this->id]],'style' => "display: none;"]
        ];
        $this->assign('navTabs',  parent::navTabs($nav));

        if(empty(input('group'))) {
            $_GET['group'] = 1;
        }

    }

    public function index()
    {
        $model = CategoryBls::getCategoryList();
        $this->formatCategory($model->getCollection());

        $html = (new Tree('parent_id'))->create($model->getCollection(), function($date) {
            function recursion($date){

                $html = '';
                foreach ($date as $value) {
                    $html .= '<li id="list_'.$value->category_id.'"><div>
                        <span class="disclose"><span>
                        </span></span>
                        '.$value->titleName.'
                        <span style="float: right">
                            <a href="'.url('edit', ['id' => $value->category_id]).'" class="layui-btn layui-btn-normal layui-btn-mini">编辑</a>
                            <a href="javascript:;" date='.$value->category_id.' class="layui-btn layui-btn-danger layui-btn-mini category-delete">删除</a>
                        </span>
                    </div>';

                    $child = count($value->child);
                    if($child > 0) {
                        $html .= '<ol>';
                        $html .= recursion($value->child);
                        $html .= '</ol>';
                    }
                    $html .= '</li>';
                }
                return $html;
            };
            return recursion($date->getItems());
        });
        return $this->fetch('', [
            'html' => $html
        ]);
    }

    public function add()
    {
        return $this->fetch('category', [
            'category'  => CategoryBls::getTreeCategory(input('group')),
            'page'      => PageBls::getAllPage()
        ]);
    }

    public function create()
    {
        if($this->request->isPost()){
            $data = $this->request->post();
            $result = $this->validate($data,'app\common\bls\category\validate\CategoryValidate.category');
            if(true !== $result){
                // 验证失败 输出错误信息
                return $this->error($result);
            }

            if(CategoryBls::createCategory($data)){
                return $this->success(lang('success'), url('index'));
            }else {
                return $this->error(lang('failed'));
            }
        }

        return $this->error('参数错误');
    }

    public function edit()
    {
        $model = CategoryBls::getOneCategory(['category_id'=>input('id')]);
        return $this->fetch('category', [
            'category'  => CategoryBls::getTreeCategory($model->group),
            'page'      => PageBls::getAllPage(),
            'info'      => $model
        ]);
    }

    public function update()
    {
        if($this->request->isPost()){
            $data = $this->request->post();
            $result = $this->validate($data,'app\common\bls\category\validate\CategoryValidate.category');
            if(true !== $result){
                // 验证失败 输出错误信息
                return $this->error($result);
            }
            $model = CategoryBls::getOneCategory(['category_id' => $this->id]);

            if(empty($model)){
                return $this->error('参数错误');
            }

            if($model->save($data)) {
                return $this->success(lang('update success'), url('index'));
            }else {
                return $this->error(lang('update failed'));
            }

        }

    }

    public function delete()
    {
        if($this->request->isDelete()) {
            $model = CategoryBls::getOneCategory(['category_id' => input('id')]);

            if (!$model) {
                return $this->error('参数错误');
            }

            if ($model->subsetNum() > 0) {
                return $this->error('请先删除子集导航');
            }

            if($model->delete()){
                $this->success(lang('delete success'));
            }
        }

        return $this->error(lang('delete failed'));
    }

    public function sort()
    {
        $request = $this->request->post();

        if(CategoryBls::categorySort($request['date'])){
            return $this->success(lang('success'));
        }else {
            return $this->error(lang('failed'));
        }
    }


}