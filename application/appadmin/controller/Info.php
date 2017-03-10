<?php
namespace app\appadmin\controller;

use app\common\model\CategoryModel;
use app\common\model\InfoModel;

class Info extends BasicController
{
    private $id;
    private $terminal;
    private $recommendation = ['1'=>'最新推荐', '2'=>'热门推荐'];
    private $url            = 'info/index';
    private $validate = [
        ['title|标题', 'require']
    ];

    public function __construct()
    {
        parent::__construct();

        $param          = $this->request->param();
        $this->id       = intval(array_get($param, 'id'));
        $this->terminal = array_get($param, 'terminal',current(Category::$terminal)); //默认Category控制器$terminal第一个数据
        $nav = [
            '信息列表' => ['url' => [$this->url, ['terminal' => $this->terminal]]],
            '信息增加' => ['url' => ['info/add', ['terminal' => $this->terminal]]],
            '信息修改' => ['url' => ['info/edit', ['id' => $this->id, 'terminal' => $this->terminal]], 'style' => "display: none;"],
        ];
        $this->assign('navTabs',  parent::navTabs($nav));
    }

    public function index()
    {
        //条件判断
        $where['terminal']  = $this->terminal;

        //模型join关联category查询数据
        $list = InfoModel::with('category')
            ->where($where)
            ->order(["sort" => "desc", 'info_id' => 'desc'])
            ->paginate();


        return $this->fetch('',[
            'list'  => $list,
            'page'  => $list->render(),
            'enum'  => ['terminal' => Category::$terminal]
        ]);
    }

    /**
     * 信息增加
     */
    public function add()
    {
        return $this->fetch('',[
            'info'  => '',
            'enum'  => self::enum(['category_id' => intval($this->request->param('category_id'))])
        ]);
    }

    /**
     * 信息创建
     */
    public function create()
    {
        if($this->request->isPost()){

            $post   = InfoModel::recombinantArray($this->request->post(), 'photos');

            //数据验证
            $result = $this->validate($post, $this->validate);
            if($result !== true){
                return $this->error($result);
            }

            //写入数据库
            if(InfoModel::create($post)){
                return $this->success(lang('Add success'), url($this->url));
            }else{
                return $this->error(lang('Add failed'));
            }
        }
        return abort(404, lang('404 not found'));
    }

    /**
     * 信息修改
     */
    public function edit()
    {
        $info    = InfoModel::get($this->id);
        if(empty($info)){
            return abort(404, lang('404 not found'));
        }

        return $this->fetch('', [
            'info'  => $info,
            'enum'  => self::enum(['category_id' => $info['category_id']])
        ]);
    }

    /**
     * 信息更新
     */
    public function update(){

        //edit_post 数据处理
        if($this->request->isPost()){

            $post   = InfoModel::recombinantArray($this->request->post(), 'photos');
            //数据验证
            $result = $this->validate($post,$this->validate);
            if($result !== true){
                return $this->error($result);
            }

            //查询数据
            $query  = InfoModel::get($this->id);
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
     * 信息删除
     */
    public function delete()
    {
        if($this->request->isPost() && !empty($this->id)){
            $delete  = InfoModel::get($this->id);

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

    /**
     * 信息排序
     */
    public function sort()
    {
        $sort   = InfoModel::get($this->id);
        $order  = isset($_POST['order']) ? intval($_POST['order']) : 0;
        if(!$this->request->isPost() || empty($sort)){
            return abort(404, lang('404 not found'));
        }

        //更新数据
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
    private function enum($arr = [])
    {
        $arr['terminal']    = $this->terminal;
        return [
            'recommendation'    => $this->recommendation,
            'category'          => CategoryModel::treeCategory($arr['category_id'], $arr['terminal']),
        ];
    }
}