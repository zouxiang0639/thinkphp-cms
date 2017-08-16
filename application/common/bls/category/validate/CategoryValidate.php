<?php
namespace app\common\bls\category\validate;


use app\common\base\BaseValidate;
use app\common\consts\category\CategoryBindPageConst;

class CategoryValidate extends BaseValidate
{

    public function setRule()
    {
        return  [
            'group'  => 'require',
            'title'   => 'requireIf:bind_page,'.CategoryBindPageConst::LINKS,
            'bind_page' => 'require',
        ];
    }

    public function setMessage()
    {
        return [
            'group.require' => '导航分类必须',
            'title.requireIf' => '标题不能为空',
            'bind_page.require' => '请选择绑定页面',
        ];
    }

    public function setScene()
    {
        return [
            'category'  =>  ['group', 'title', 'bind_page']
        ];
    }

}