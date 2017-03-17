<?php
namespace app\manage\controller;

use app\common\model\FragmentModel;

class Fragment extends BasicController
{

    private $id     = 0;
    private $url    = 'fragment/index';

    private $validate =[
        ['title|标题', 'require']
    ];

    public function __construct()
    {
        parent::__construct();
        $this->id       = !empty($this->request->param('id')) ? intval($this->request->param('id')) : $this->id;
        $nav = [
            '碎片列表' => ['url' => $this->url],
            '碎片增加' => ['url' => 'fragment/add'],
            '碎片修改' => ['url' => ['fragment/edit', ['id' => $this->id]], 'style' => "display: none;"],
        ];
        $this->assign('navTabs',  parent::navTabs($nav));
    }

    public function index()
    {

        $list = FragmentModel::paginate();
        return $this->fetch('',[
            'list'      => $list,
            'page'      => $list->render(),
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
            $result = $this->validate($post, $this->validate);
            if($result !== true){
                return $this->error($result);
            }

            //写入数据库
            if(FragmentModel::create($post)){
                return $this->success(lang('Add success'), url($this->url));
            }else{
                return $this->error(lang('Add failed'));
            }

        }

        return $this->fetch('',[
            'info'  => [
                'types'     =>[1]
            ]
        ]);
    }

    /**
     * 碎片修改
     */
    public function edit()
    {

        $info = FragmentModel::get($this->id);
        if(empty($info)){
            return abort(404, lang('404 not found'));
        }
        return $this->fetch('',[
            'info'   => $info
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
            $result = $this->validate($post, $this->validate);
            if($result !== true){
                return $this->error($result);
            }

            //更新数据库
            $update = FragmentModel::get($this->id);
            if(empty($update)){
                return abort(404, lang('404 not found'));
            }
            if($update->save($post)){
                return $this->success(lang('Update success'), url($this->url));
            }else{
                return $this->error(lang('Update failed'));
            }
        }

        return abort(404, lang('404 not found'));
    }

    /**
     * 碎片删除
     */
    public function delete()
    {
        if($this->request->isPost() && !empty($this->id)){
            $delete  = FragmentModel::get($this->id);
            if(empty($delete)){
                return abort(404, lang('404 not found'));
            }
            if($delete->delete()){
                return $this->success(lang('delete success'), url($this->url));
            }else{
                return $this->error(lang('delete failed'));
            }
        }
        return abort(404, lang('404 not found'));
    }

}