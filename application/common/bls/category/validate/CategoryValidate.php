<?php
namespace app\common\bls\category\validate;


use think\Validate;

class CategoryValidate extends Validate
{
    protected $rule =   [
        'group'  => 'require',
        'title'   => 'require',
        'bind_page' => 'require',
    ];

    protected $message  =   [
        'group.require' => '导航分类必须',
        'title.require' => '标题不能为空',
        'bind_page.require' => '请选择绑定页面',
    ];

    protected $scene = [
        'category'  =>  ['group', 'title', 'bind_page']
    ];

}