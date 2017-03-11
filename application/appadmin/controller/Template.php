<?php
namespace app\appadmin\controller;

use app\common\model\TemplateModel;

class Template extends BasicController
{

    private $id     = 0;
    private $url    = 'template/index';
    static $groups  = [
        1 => '单页模型',
        2 => '信息模型'
    ];

    static $builderMenu  = [
        //生成菜单[ 组类型, menuID,   路由]
        1   => ['单页模型', 62, 'category/edit'],
        2   => ['信息模型', 62, 'info/index']
    ];

    static $type    = [
        '分类页面'  => '分类页面',
        '信息内页'  => '信息内页',
        '通用页面'  => '通用页面'
    ];
    private $validate =[
        ['title|标题', 'require'],
        ['template_file|模版文件', 'require|alphaDash'],
    ];

    public function __construct()
    {
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
            $where['group']  = array_get(self::$groups,$groups);
        }

        $list = TemplateModel::where($where)->paginate(20);
        return $this->fetch('',[
            'list'  => $list,
            'page'  => $list->render(),
            'groups'  => self::$groups
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
                'groups'    => self::$groups,
                'types'     => self::$type
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
        $info['groups'] = self::$groups;
        $info['types']  = self::$type;
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