<?php
namespace app\manage\controller;

use app\common\bls\extended\ExtendedBls;
use app\common\bls\forms\FormsBls;
use app\common\bls\forms\traits\FormsTrait;
use app\common\consts\extended\ExtendedTypeConst;

class Forms extends BasicController
{
    use FormsTrait;


    public function __construct()
    {
        parent::__construct();
        $nav = [
            '表单列表' => ['url' => 'index'],
            '表单详细' => ['url' => ['show', ['id' => input('id')]], 'style' => "display: none;"],
        ];
        $this->assign('navTabs',  parent::navTabs($nav));
    }

    /**
     * 表单列表
     */
    public function index()
    {
        $where = [];
        if(!empty(input('type'))) {
            $where['extended_id'] = input('type');
        }

        $model = FormsBls::getFormsList($where);
        $this->formatForm($model->getCollection());
        $extended = ExtendedBls::getExtendedType(ExtendedTypeConst::FORM);

        return $this->fetch('index', [
            'list' => $model,
            'extended' => $extended
        ]);
    }

    /**
     * 表单详情
     */
    public function show()
    {
        $model = FormsBls::getOneForm(['forms_id'=>input('id')]);

        $html = ExtendedBls::formBuilder($model->extended_id, $model->extend);
        return $this->fetch('show', [
            'html' => $html
        ]);
    }

    /**
     * 删除
     */
    public function delete()
    {
        if($this->request->isPost()){
            $model  = FormsBls::getOneForm(['forms_id' => input('id')]);
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