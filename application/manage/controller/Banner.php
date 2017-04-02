<?php
namespace app\manage\controller;

use app\common\model\BannerModel;

class Banner extends BasicController
{

    private $id     = 0;
    private $url    = 'banner/index';
    private $groups  = [];  //请在语言包里设置

    private $validate =[
        ['title|标题', 'require']
    ];

    public function __construct()
    {
        $this->groups   = lang('banner groups');

        parent::__construct();
        $this->id       = !empty($this->request->param('id')) ? intval($this->request->param('id')) : $this->id;
        $nav = [
            '幻灯片列表' => ['url' => $this->url],
            '幻灯片增加' => ['url' => 'banner/add'],
            '幻灯片修改' => ['url' => ['banner/edit', ['id' => $this->id]], 'style' => "display: none;"],
        ];
        $this->assign('navTabs',  parent::navTabs($nav));
    }

    public function index()
    {

        $where  = [];
        $groups   = intval($this->request->get('groups'));
        if(!empty($groups)){
            $where['groups']  = $groups;
        }

        $list = BannerModel::where($where)->paginate();
        return $this->fetch('',[
            'list'      => $list,
            'page'      => $list->render(),
            'groups'    => $this->groups
        ]);
    }

    /**
     * 幻灯片添加
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
            if(BannerModel::create($post)){
                return $this->success(lang('Add success'), url($this->url));
            }else{
                return $this->error(lang('Add failed'));
            }

        }

        return $this->fetch('',[
            'info'  => [
                'group'    => $this->groups,
                'types'     =>[1]
            ]
        ]);
    }

    /**
     * 幻灯片修改
     */
    public function edit()
    {

        $info = BannerModel::get($this->id);
        if(empty($info)){
            return abort(404, lang('404 not found'));
        }
        $info['group'] = $this->groups;
        return $this->fetch('',[
           'info'   => $info
        ]);
    }

    /**
     * 幻灯片更新
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
            $update = BannerModel::get($this->id);
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
     * 幻灯片删除
     */
    public function delete()
    {
        if($this->request->isPost() && !empty($this->id)){
            $delete  = BannerModel::get($this->id);
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