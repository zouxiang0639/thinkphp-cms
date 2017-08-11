<?php
namespace app\manage\controller;

use app\common\bls\info\InfoBls;
use app\common\bls\page\PageBls;
use app\common\consts\common\CommonStatusConst;
use app\common\consts\page\PageTemplateConst;
use app\common\model\CategoryModel;
use app\common\model\ExtendedModel;
use app\common\model\InfoModel;
use app\common\tool\Helper;

class Info extends BasicController
{
    private $id;
    private $cid;
    private $recommendation = [];
    private $url            = 'info/index';
    private $validate = [
        ['title|标题', 'require']
    ];

    public function __construct()
    {
        $this->recommendation   = lang('info recommendation');
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
        $param = $this->request->param();
        $where          = [];
        if(!empty($param['page'])) {
            $where['page_id'] = $param['page'];
        }

        if(!empty($param['title'])) {
            $where['title'] = $param['title'];
        }

        $list = InfoBls::getInfoList($where);
        $page = PageBls::getAllPage(['template_type' => PageTemplateConst::INFO[0]]);

        return $this->fetch('',[
            'list'  => $list,
            'page'  => $page
        ]);
    }

    /**
     * 信息增加
     */
    public function add()
    {
       /* $category   = CategoryModel::get($this->cid);
        $extendeds  = ExtendedModel::formBuilder($category['data_extended_id']);*/

        return $this->fetch('info',[
            'info'  => ['category_id' => $this->cid, 'extendeds' => ''],
            'enum'  => self::enum()
        ]);
    }

    /**
     * 信息创建
     */
    public function create()
    {
        if($this->request->isPost()){

            $post   = Helper::recombinantArray($this->request->post(), 'photos');
            //数据验证
            $result = $this->validate($post, $this->validate);
            if($result !== true){
                return $this->error($result);
            }


          /*  //检查是否有数据库扩展
            $infoModel      =  PageBls::checkExtended($post['page_id']);
            if($infoModel->extendedsModel){
                $extendedsModel         = new $infoModel->extendedsModel($post['extend']);
                $infoModel->extendeds   = $extendedsModel;
            }*/

            //关联数据库扩展模型数据更新
            if(InfoBls::createInfo($post)){
                return $this->success(lang('Add success'), url('index'));
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
        $category           = CategoryModel::get($info['category_id']);

        //检查是否有数据扩展
        $extendModel        = $info->checkExtended(false);
        $extendeds          = [];
        if(!empty($extendModel->extendedsModel)){
            $extendeds      = $extendModel->extendeds;
        }else{
            $extendeds      = $info['extend'];
        }

        $info['extendeds']  = ExtendedModel::formBuilder($category['data_extended_id'], $extendeds);

        return $this->fetch('', [
            'info'  => $info,
            'enum'  => self::enum()
        ]);
    }

    /**
     * 信息更新
     */
    public function update(){

        //edit_post 数据处理
        if($this->request->isPost()){

            $post   = Helper::recombinantArray($this->request->post(), 'photos');

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

            //检查是否有数据库扩展
            $update = $query->checkExtended();
            if($update->extendedsModel){
                foreach($post['extend'] as $k=>$v){
                    $update->extendeds->$k  = $v;
                }
                unset($post['extend']);
            }

            //关联数据库扩展模型数据更新
            if($update->save($post)){
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

            //关联数据库扩展模型数据删除
            if($delete->checkExtended()->delete()){
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
     * @return array
     */
    private function enum()
    {
        return [
            'recommendation'    => $this->recommendation,
            'page'              => PageBls::getAllPage(['template_type' => PageTemplateConst::INFO[0]]),
            'display'           => CommonStatusConst::desc()
        ];
    }
}