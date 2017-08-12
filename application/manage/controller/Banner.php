<?php
namespace app\manage\controller;

use app\common\bls\banner\BannerBls;
use app\common\bls\banner\traits\BannerTrait;

class Banner extends BasicController
{
    use BannerTrait;

    private $id     = 0;

    public function __construct()
    {
        parent::__construct();
        $this->id       = !empty($this->request->param('id')) ? intval($this->request->param('id')) : $this->id;
        $nav = [
            '幻灯片列表' => ['url' => 'index'],
            '幻灯片增加' => ['url' => 'add'],
            '幻灯片修改' => ['url' => ['edit', ['id' => $this->id]], 'style' => "display: none;"],
        ];
        $this->assign('navTabs',  parent::navTabs($nav));
    }

    public function index()
    {

        $where  = [];
        $type   = $this->request->get('type');
        if(!empty($type)){
            $where['type']  = $type;
        }
        $model = BannerBls::getBannerList($where);
        $this->formatBanner($model->getCollection());

        return $this->fetch('',[
            'list'      => $model,
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

            $result = $this->validate($post, 'app\common\bls\banner\validate\BannerValidate.create');
            if(true !== $result){
                // 验证失败 输出错误信息
                return $this->error($result);
            }

            //写入数据库
            if(BannerBls::createBanner($post)){
                return $this->success(lang('Add success'), url('index'));
            }else{
                return $this->error(lang('Add failed'));
            }

        }

        return $this->fetch('banner');
    }

    /**
     * 幻灯片修改
     */
    public function edit()
    {

        $model = BannerBls::getOneBanner(['banner_id'=>$this->id]);
        if(empty($model)){
            return $this->error('参数错误');
        }
        return $this->fetch('banner',[
           'info'   => $model
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
            $result = $this->validate($post, 'app\common\bls\banner\validate\BannerValidate.update');
            if(true !== $result){
                // 验证失败 输出错误信息
                return $this->error($result);
            }

            //更新数据库
            $update = BannerBls::getOneBanner(['banner_id'=>$this->id]);
            if(empty($update)){
                return $this->error('参数错误');
            }

            if($update->save($post)){
                return $this->success(lang('Update success'), url('index'));
            }else{
                return $this->error(lang('Update failed'));
            }
        }

        return $this->error('参数错误');
    }

    /**
     * 幻灯片删除
     */
    public function delete()
    {
        if($this->request->isPost() && !empty($this->id)){
            $model  = BannerBls::getOneBanner(['banner_id'=>$this->id]);
            if(empty($model)){
                return $this->error('参数错误');
            }
            if($model->delete()){
                return $this->success(lang('delete success'), url('index'));
            }else{
                return $this->error(lang('delete failed'));
            }
        }
        return $this->error('参数错误');
    }

}