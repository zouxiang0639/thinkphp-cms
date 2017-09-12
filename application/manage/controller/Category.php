<?php
namespace app\manage\controller;

use app\common\bls\category\CategoryBls;
use app\common\bls\category\traits\CategoryTrait;
use app\common\bls\page\PageBls;
use app\common\consts\category\CategoryGroupConst;
use app\common\library\trees\Tree;
use app\common\library\weChat\WeChatSdk;


class Category extends BasicController
{

    use CategoryTrait;

    private $id;

    public function __construct()
    {
        parent::__construct();

        $this->id       = intval(array_get($this->request->param(), 'id'));
        if(empty(input('group'))) {
            $_GET['group'] = 1;
        }
        $nav = [
            '导航列表' => ['url' => ['index', ['group' => input('group')]]],
            '导航添加' => ['url' => ['add', ['group' => input('group')]]],
            '导航修改' => ['url' => ['edit',['id'=> $this->id, 'group' => input('group')]],'style' => "display: none;"]
        ];
        $this->assign('navTabs',  parent::navTabs($nav));



    }

    public function index()
    {
        $param = $this->request->param();
        $where          = [];
        if(!empty($param['group'])) {
            $where['group'] = $param['group'];
        }

        $model = CategoryBls::getCategoryList($where);
        $this->formatCategory($model->getCollection());

        $html = (new Tree('parent_id'))->create($model->getCollection(), function($date) {
            function recursion($date){

                $html = '';
                foreach ($date as $value) {
                   /* if($value->category_id == 6){
                        dump($value);die;
                    }*/

                    $page = $value->url ? '<a target="_blank" href="'.$value->url.'" >内部页面</a>' : '';

                    $html .= '<li id="list_'.$value->category_id.'"><div>
                        <span class="disclose"><span>
                        </span></span>
                        '.$value->titleName.'
                        <span style="float: right">
                            '.$page.'
                            <a href="'.url('edit', ['id' => $value->category_id]).'" >编辑</a>
                            <a href="javascript:;" date='.$value->category_id.' class="category-delete">删除</a>
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
                return $this->success(lang('success'), url('index', ['group' => input('group')]));
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

            if(CategoryBls::updateCategory($model,$data)) {
                return $this->success(lang('update success'), url('index',['group' => input('group')]));
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

    /**
     * 微信公众导航生成器
     */
    public function weChatMenu()
    {
        $model = CategoryBls::getCategorySelect(['group' => CategoryGroupConst::WE_CHAT]);
        $menu = (new Tree('parent_id'))->create($model, function($date) {
            $menu = [];
            $date = $date->getItems();
            foreach ($date as $value) {
                $subButton = [];
                foreach($value->child as $items) {
                    $subButton[] = [
                        'type'  => 'view',
                        'name'  => $items->title,
                        'url'   => $items->links
                    ];
                }

                $menu[] = [
                    'name'      => $value->title,
                    'sub_button'=> $subButton
                ];
            }
            return  [
                'button'=> $menu
            ];
        });
        $sdk = new WeChatSdk();
        $sdk->getAccessToken();
        $sdk->createNav($menu);

    }

    public function aaa()
    {
        return $this->success('chengg');
    }
}