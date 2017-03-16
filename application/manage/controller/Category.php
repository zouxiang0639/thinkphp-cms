<?php
namespace app\manage\controller;

use app\common\model\CategoryModel;
use app\common\model\TemplateModel;
use thinkcms\auth\library\Tree;

class Category extends BasicController
{
    private $id         = 0;
    private $url        = 'category/index';
    private $display    = [];  //请到语言包里修改 template groups
    private $groups     = [];   //请到语言包里修改 display
    private $validate   =[
        ['title|标题', 'require'],
    ];

    public function __construct()
    {
        parent::__construct();

        $this->groups       = lang('template groups');
        $this->display      = lang('display');
        $this->id           = intval(array_get($this->request->param(), 'id'));

        $nav = [
            '分类列表' => ['url' => $this->url],
            '分类增加' => ['url' => 'category/add'],
            '分类修改' => ['url' => ['category/edit', ['id' => $this->id]], 'style' => "display: none;"],
        ];
        $this->assign('navTabs',  parent::navTabs($nav));
    }

    public function index()
    {
        $query     = CategoryModel::where('')
            ->order(["sort" => "asc",'category_id'=>'asc'])
            ->column('category_id, title, sort, parent_id', 'category_id');

        if(!empty($query)){ //有数据处理数据

            $tree       = new Tree();
            $tree->nbsp = '&nbsp;&nbsp;&nbsp;';

            //前端分类树形处理
            foreach ($query as $n=> $r) {
                $query[$n]['level'] = $tree->get_level($r['category_id'], $query);
                $query[$n]['parent_id_node'] = ($r['parent_id']) ? ' class="child-of-node-' . $r['parent_id'] . '"' : '';

                $query[$n]['child_add'] = checkPath('category/add', ["parent_id" => $r['category_id']]) ?
                    url("category/add", ["parent_id" => $r['category_id']]) : '';
                $query[$n]['str_manage'] = checkPath('category/edit',["id" => $r['category_id']]) ?
                    '<a href="'.url("category/edit", ["id" => $r['category_id']]).'">编辑</a> |' : '';
                $query[$n]['str_manage'] .= checkPath('category/delete',["id" => $r['category_id']]) ?
                    '<a class="a-post" post-msg="你确定要删除吗" post-url="'.url("category/delete", ["id" => $r['category_id']]).'">删除</a>' : '';
            }
            $tree->init($query);
            unset($result); //释放内存

            //树形菜单html生成
            $str = "<tr id='node-\$id' \$parent_id_node>
                    <td style='padding-left:20px;'>
                        <input name='sort[\$id]' type='text' size='3' value='\$sort' data='\$id' class='listOrder'>
                    </td>
                    <td>\$category_id</td>
                    <td>\$spacer \$title <a href='\$child_add'>【✚】</td>
                    <td>\$str_manage</td>
                </tr>";
            $html = $tree->get_tree(0, $str);

        }else{//没有数据返回问候语

            $html = "<tr><td colspan='4'>没有数据</td></tr>";
        }

        return $this->fetch('', [
            'html'      => $html
        ]);
    }

    /**
     * 分类增加
     */
    public function add(){


        if($this->request->isPost()){
            $post   = CategoryModel::recombinantArray($this->request->post(), 'pictures');

            //数据验证
            $result = $this->validate($post, $this->validate);
            if($result !== true){
                return $this->error($result);
            }

            //写入数据库
            if(CategoryModel::create($post)){
                return $this->success(lang('Add success'), url($this->url));
            }else{
                return $this->error(lang('Add failed'));
            }
        }
        return $this->fetch('',[
            'enum' => self::enum(['parent_id' => intval($this->request->param('parent_id'))]),
            'info' => []
        ]);
    }

    /**
     * 分类修改
     */
    public function edit()
    {
        $info = CategoryModel::get($this->id);
        $info->extendeds;
        if(empty($info)){
            return abort(404, lang('404 not found'));
        }
        return $this->fetch('',[
            'enum' => self::enum(['parent_id' => $info['parent_id']]),
            'info' => $info
        ]);
    }

    /**
     * 分类更新
     */
    public function update(){

        //edit_post 数据处理
        if($this->request->isPost()){

            $post   = CategoryModel::recombinantArray($this->request->post(), 'photos');
            //数据验证
            $result = $this->validate($post,$this->validate);
            if($result !== true){
                return $this->error($result);
            }

            //查询数据
            $query  = CategoryModel::get($this->id);
            if(empty($query)){
                return abort(404, lang('404 not found'));
            }

            //修改数据库
            if($query->save($post)){
                return $this->success(lang('Update success'),$this->url);
            }else{
                return $this->error(lang('Update failed'));
            }
        }
        return abort(404, lang('404 not found'));
    }

    /**
     * 分类删除
     */
    public function delete()
    {
        $delete = CategoryModel::get($this->id);

        if(!$this->request->isPost() || empty($delete)){
            return abort(404, lang('404 not found'));
        }

        //数据删除
        if($delete->delete()){
            return $this->success(lang('Delete success'),url($this->url));
        }else{
            return $this->error(lang('Delete failed'));
        }
    }

    /**
     * 分类排序
     */
    public function sort()
    {
        $sort   = CategoryModel::get($this->id);
        $order  = isset($_POST['order']) ? intval($_POST['order']) : 0;
        if(!$this->request->isPost() || empty($sort)){
            return abort(404, lang('404 not found'));
        }

        //数据删除
        if($sort->save(['sort' => $order])){
            return $this->success(lang('Sort success'),url($this->url));
        }else{
            return $this->error(lang('Sort failed'));
        }
    }

    /**
     * 枚举数组
     *
     * @param  array      $arr
     * @return array
     */
    private function enum($arr)
    {
      return  [
            'display'            => $this->display,
            'template_group'     => $this->groups,
            'template_default'   => TemplateModel::tplTypeLife(['分类页面', '通用页面']),
            'template_info'      => TemplateModel::tplTypeLife(['信息内页', '通用页面']),
            'parent'             => CategoryModel::treeCategory($arr['parent_id'])
        ];
    }

}