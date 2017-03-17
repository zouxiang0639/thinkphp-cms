<?php
namespace app\manage\controller;

use app\common\model\TemplateModel;

class Template extends BasicController
{

    private $id     = 0;
    private $url    = 'template/index';
    private $type   = [];       //修改请到语言包template type修改
    private $groups = [];       //修改请到语言包template groups修改
    private $builderMenu  = []; //修改请到语言包template builder menu修改
    private $validate =[
        ['title|标题', 'require'],
        ['template_file|模版文件', 'require|alphaDash'],
    ];

    public function __construct()
    {
        $this->groups       = lang('template groups');
        $this->type         = lang('template type');
        $this->builderMenu  = lang('template builder menu');
        parent::__construct();
        $this->id       = !empty($this->request->param('id')) ? intval($this->request->param('id')) : $this->id;
        $nav = [
            '模版文件列表' => ['url' => $this->url],
            '模版文件增加' => ['url' => 'template/add'],
            '模版文件修改' => ['url' => ['template/edit', ['id' => $this->id]], 'style' => "display: none;"],
        ];
        $this->assign('navTabs',  parent::navTabs($nav));
    }

    public function index()
    {
        $where  = [];
        $groups   = intval($this->request->get('groups'));
        if(!empty($groups)){
            $where['group']  = $groups;
        }

        $list = TemplateModel::where($where)->paginate();
        return $this->fetch('',[
            'list'      => $list,
            'page'      => $list->render(),
            'groups'    => $this->groups,
            'type'      => $this->type
        ]);
    }

    /**
     * 模版文件添加
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
            if(TemplateModel::create($post)){
                return $this->success(lang('Add success'), url($this->url));
            }else{
                return $this->error(lang('Add failed'));
            }

        }

        return $this->fetch('',[
            'info'  => [
                'groups'    => $this->groups,
                'types'     => $this->type
            ]
        ]);
    }

    /**
     * 模版文件修改
     */
    public function edit()
    {

        $info = TemplateModel::get($this->id);
        if(empty($info)){
            return abort(404, lang('404 not found'));
        }
        $info['groups'] = $this->groups;
        $info['types']  = $this->type;
        return $this->fetch('',[
           'info'   => $info
        ]);
    }

    /**
     * 模版文件更新
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
            $update = TemplateModel::get($this->id);
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
     * 模版文件删除
     */
    public function delete()
    {
        if($this->request->isPost() && !empty($this->id)){
            $delete  = TemplateModel::get($this->id);
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