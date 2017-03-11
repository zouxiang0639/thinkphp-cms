<?php
namespace app\appadmin\controller;

use app\common\model\CategoryModel;
use app\common\model\InfoModel;

class Info extends BasicController
{
    private $id;
    private $cid;
    private $recommendation = ['1'=>'最新推荐', '2'=>'热门推荐'];
    private $display        = ['所有人可见' => '所有人可见', '不可见' => '不可见', '管理员可见' => '管理员可见'];  //枚举enum字段 如果有变动需要修改数据库;
    private $url            = 'info/index';
    private $validate = [
        ['title|标题', 'require']
    ];

    public function __construct()
    {
        parent::__construct();

        $this->id       = intval(array_get($this->request->param(), 'id'));
        $this->cid      = intval(array_get($this->request->param(), 'cid', $this->id));
        $nav = [
            '信息列表' => ['url' => [$this->url, ['cid' => $this->cid]]],
            '信息增加' => ['url' => ['info/add', ['cid' => $this->cid]]],
            '信息修改' => ['url' => ['info/edit', ['id' => $this->id, 'cid' => $this->cid]], 'style' => "display: none;"],
        ];

        $this->assign([
            'navTabs'   => parent::navTabs($nav),
            'cid'       => $this->cid
        ]);
    }

    public function index()
    {
        //条件判断
        $where          = [];
        if($this->cid) {
            $where['info_model.category_id'] = $this->cid;
        }

        //模型join关联category查询数据
        $list = InfoModel::with('category')
            ->where($where)
            ->order(["sort" => "desc", 'info_id' => 'desc'])
            ->paginate();


        return $this->fetch('',[
            'list'  => $list,
            'page'  => $list->render()
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
                return $this->success(lang('Add success'), url($this->url, ['cid' => $this->cid]));
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

            //更新数据
            if($query->save($post)){
                return $this->success(lang('Update success'), url($this->url, ['cid' => $this->cid]));
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
                return $this->success(lang('delete success'));
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
            return $this->success(lang('Sort success'));
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
        $arr = array_merge(['category_id' => $this->cid], $arr);
        return [
            'recommendation'    => $this->recommendation,
            'category'          => CategoryModel::treeCategory($arr['category_id']),
            'display'          => $this->display
        ];
    }
}