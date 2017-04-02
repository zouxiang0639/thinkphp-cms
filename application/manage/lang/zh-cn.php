<?php


return [

    //幻灯片控制器
    'banner groups'             => [1 => "首页banner", 2 => "底部相册"],

    //配置控制器
    'configure groups'          => [1 => '基本配置', 2 => '邮箱配置'],

    //信息控制器
    'info recommendation'       => [1 => '最新推荐', 2 => '热门推荐'],

    //信息控制器
    'mysql fields type'         => [1 => 'tinyint', 2 => 'int', 3 => 'mediumtext', 4 => 'timestamp' , 5 => 'float'],

    //文件控制器
    'file groups'               => [1 => '图片', 2 => '文件', 3 => '视屏', 4 => '音频'],

    //扩展控制器
    'extended groups'           => [1 => '字段类型', 2 => '数据库类型'],

    //模版控制器
    'template groups'           => [1 => '单页模型', 2 => '信息模型', 3 => '产品模型'],
    'template type'             => [1 => '分类页面', 2 => '信息内页', 3 => '通用页面'],
    'template builder menu'     => [ //生成菜单[ 组类型, menuID,   路由]
                                        1   => ['单页模型', 32, 'category/edit'],
                                        2   => ['信息模型', 32, 'info/index']
                                    ],
    //公共
    'display'                   => [1 => '所有人可见', 2 => '不可见', 3 => '管理员可见'],
    'form type'                 => [
                                    'text'      => '文本框',
                                    'select'    => '下拉框',
                                    'checkbox'  => '复选框',
                                    'radio'     => '单选框',
                                    'password'  => '密码框',
                                    'textarea'  => '文本域'
                                ],

    // 错误提示
    '404 not found'             => '页面不存在!',
    'Data cannot be empty'      => '请选择要操作的数据!',
    'Data cannot be operated'   => '数据不可操作',

    //操作数据提示
    'Success'                   => '操作成功',
    'Failed'                    => '操作失败',
    'Add success'               => '增加成功',
    'Add failed'                => '增加失败',
    'Edit success'              => '更新成功',
    'Edit failed'               => '更新失败',
    'Update success'            => '更新成功',
    'Update failed'             => '更新失败',
    'Delete success'            => '删除成功',
    'Delete failed'             => '删除失败',
    'Ratify success'            => '启用成功',
    'Ratify failed'             => '启用失败',
    'Sort success'              => '排序成功',
    'Sort failed'               => '没有变化',
    'Forbidden success'         => '禁用成功',
    'Forbidden failed'          => '禁用失败',
    'Clear data success'        => '数据清空成功',
    'Clear data failed'         => '数据清空失败',
    'no data'                   => '没有数据'
];
