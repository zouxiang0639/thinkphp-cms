<?php

use think\migration\Seeder;
use thinkcms\auth\model\Menu as MenuModel;

class Menu extends \Phinx\Seed\AbstractSeed
{
    //菜单ID
    public $menuid = 1;

    //菜单数据
    public $data = [];

    /**
     * Run Method.
     */
    public function run()
    {
       $array = [
           $this->menuSystem(),
           $this->menuArticle(),
           $this->menuOther(),
           $this->menuExtendedTool()
       ];

       foreach($array as $v){
           $this->factory($v);
           $parent_id   = $this->menuid;
           $this->menuid ++;
           $this->recursion($v['child'], $parent_id);
       }
        $table = $this->table('menu');
        $table->insert($this->data);
        $table->saveData();
    }

    /**
     * 菜单递归
     */
    public function recursion($data, $praent_id = 0)
    {
        foreach($data as $v){
            if(!isset($v['parent_id'])){
                $v = array_merge($v,['parent_id' => $praent_id]);
            }
            $this->factory($v);
            $parent_id   = $this->menuid;
            $this->menuid ++;
            if(isset($v['child']) && count($v['child'])){
                $this->recursion($v['child'], $parent_id);
            }

        }
    }

    /**
     * 菜单工厂
     */
    public function factory($date)
    {
       $add[] = [
            'name'          => array_get($date, 'name'),
            'parent_id'     => array_get($date, 'parent_id', 0),
            'app'           => array_get($date, 'app'),
            'model'         => array_get($date, 'model'),
            'action'        => array_get($date, 'action'),
            'url_param'     => array_get($date, 'url_param', ''),
            'type'          => array_get($date, 'type', 1),
            'status'        => array_get($date, 'status', 1),
            'icon'          => array_get($date, 'icon', ''),
            'remark'        => array_get($date, 'remark', ''),
            'list_order'    => array_get($date, 'list_order', 0),
            'rule_param'    => array_get($date, 'rule_param', ''),
            'request'       => array_get($date, 'request', ''),
            'log_rule'      => array_get($date, 'log_rule', ''),
            'nav_id'        => array_get($date, 'nav_id', ''),
           ];

       $this->data = array_merge($this->data, $add);
    }

    /**
     * 系统管理
     */
    public function menuSystem()
    {
        $arr = [
            'name'      => '系统管理',
            'app'       => 'manage',
            'model'     => 'system',
            'action'    => 'default',
            'type'      => 0,
            'request'   => 'POST',
            'log_rule'  => '{name}',
            'child'     => array(
                [
                    'name'      => '权限管理',
                    'app'       => 'manage',
                    'model'     => 'auth',
                    'action'    => 'default',
                    'type'      => 0,
                    'status'    => 1,
                    'remark'    => 1,
                    'icon'      => 'fa-group',
                    'child'     => array(
                        [
                        'name'      => '角色管理',
                        'app'       => 'manage',
                        'model'     => 'auth',
                        'action'    => 'role',
                        'type'      => 1,
                        'status'    => 1,
                        'child'     => array(
                            [
                                'name'      => '角色增加',
                                'app'       => 'manage',
                                'model'     => 'auth',
                                'action'    => 'roleAdd',
                                'type'      => 1,
                                'status'    => 0,
                                'log_rule'  => '{id}',
                            ],
                            [
                                'name'      => '角色编辑',
                                'app'       => 'manage',
                                'model'     => 'auth',
                                'action'    => 'roleEdit',
                                'type'      => 1,
                                'status'    => 0,
                                'remark'    => 'asdas',
                            ],
                            [
                                'name'      => '角色删除',
                                'app'       => 'manage',
                                'model'     => 'auth',
                                'action'    => 'roleDelete',
                                'type'      => 1,
                                'status'    => 0
                            ],
                            [
                                'name'      => '角色授权',
                                'app'       => 'manage',
                                'model'     => 'auth',
                                'action'    => 'authorize',
                                'type'      => 1,
                                'status'    => 0
                            ]
                        )
                    ],
                    [
                        'name'      => '行为日志',
                        'app'       => 'manage',
                        'model'     => 'auth',
                        'action'    => 'log',
                        'type'      => 1,
                        'status'    => 1,
                        'child'     => array(
                            [
                                'name'      => '查看日志',
                                'app'       => 'manage',
                                'model'     => 'auth',
                                'action'    => 'viewLog',
                                'type'      => 1,
                                'status'    => 0
                            ],
                            [
                                'name'      => '清空日志',
                                'app'       => 'manage',
                                'model'     => 'auth',
                                'action'    => 'clear',
                                'type'      => 1,
                                'status'    => 0
                            ]
                        )
                    ]
                )
                ],
                [
                    'name'      => '管理员管理',
                    'app'       => 'manage',
                    'model'     => 'admin',
                    'action'    => 'default',
                    'type'      => 0,
                    'status'    => 1,
                    'child'     => array(
                        [
                            'name'      => '用户列表',
                            'app'       => 'manage',
                            'model'     => 'admin',
                            'action'    => 'index',
                            'type'      => 1,
                            'status'    => 1,
                        ],
                        [
                            'name'      => '用户增加',
                            'app'       => 'manage',
                            'model'     => 'admin',
                            'action'    => 'add',
                            'type'      => 1,
                            'status'    => 1,
                        ],
                        [
                            'name'      => '用户修改',
                            'app'       => 'manage',
                            'model'     => 'admin',
                            'action'    => 'edit',
                            'type'      => 1,
                            'status'    => 0
                        ],
                        [
                            'name'      => '用户删除',
                            'app'       => 'manage',
                            'model'     => 'admin',
                            'action'    => 'delete',
                            'type'      => 1,
                            'status'    => 0
                        ],
                        [
                            'name'      => '用户权限设置',
                            'app'       => 'manage',
                            'model'     => 'admin',
                            'action'    => 'privates',
                            'type'      => 1,
                            'status'    => 0,
                            'remark'    => '设置用户登录后台权限设置',
                        ],
                        [
                            'name'      => '修改密码',
                            'app'       => 'manage',
                            'model'     => 'admin',
                            'action'    => 'editPassword',
                            'type'      => 1,
                            'status'    => 1
                        ],
                        [
                            'name'      => '重置管理员密码',
                            'app'       => 'manage',
                            'model'     => 'admin',
                            'action'    => 'resetPassword',
                            'type'      => 1,
                            'status'    => 1
                        ]
                    )
                ],
                [
                    'name'      => '配置管理',
                    'app'       => 'manage',
                    'model'     => 'configure',
                    'action'    => 'defaule',
                    'type'      => 0,
                    'status'    => 1,
                    'child'     => array(
                        [
                            'name'      => '基本配置',
                            'app'       => 'manage',
                            'model'     => 'configure',
                            'action'    => 'basicSettings',
                            'type'      => 1,
                            'status'    => 1
                        ],
                        [
                            'name'      => '邮箱配置',
                            'app'       => 'manage',
                            'model'     => 'configure',
                            'action'    => 'emailSettings',
                            'type'      => 1,
                            'status'    => 1
                        ],
                        [
                            'name'      => '修改配置',
                            'app'       => 'manage',
                            'model'     => 'configure',
                            'action'    => 'configBuilder',
                            'type'      => 1,
                            'status'    => 0
                        ]
                    )
                ]
            )
        ];
        return $arr;
    }

    /**
     * 扩展工具
     */
    public function menuExtendedTool()
    {
        $arr = [
            'name'      => '扩展工具',
            'app'       => 'manage',
            'model'     => 'extendedTool',
            'action'    => 'default',
            'type'      => 0,
            'child'     =>  array(
                [
                    'name'      => '菜单管理',
                    'app'       => 'manage',
                    'model'     => 'auth',
                    'action'    => 'default',
                    'type'      => '0',
                    'child'     => array(
                        [
                            'name'      => '菜单列表',
                            'app'       => 'manage',
                            'model'     => 'auth',
                            'action'    => 'menu',
                            'type'      => 1,
                            'status'    => 1
                        ],
                        [
                            'name'      => '菜单增加',
                            'app'       => 'manage',
                            'model'     => 'auth',
                            'action'    => 'menuAdd',
                            'type'      => 1,
                            'status'    => 1
                        ],
                        [
                            'name'      => '菜单修改',
                            'app'       => 'manage',
                            'model'     => 'auth',
                            'action'    => 'menuEdit',
                            'type'      => 1,
                            'status'    => 0,
                            'request'   => 'POST',
                            'log_rule'  => '我的ID是{id} 记入的目录{name}',
                        ],
                        [
                            'name'      => '菜单删除',
                            'app'       => 'manage',
                            'model'     => 'auth',
                            'action'    => 'menuDelete',
                            'type'      => 1,
                            'status'    => 0
                        ],
                        [
                            'name'      => '菜单排序',
                            'app'       => 'manage',
                            'model'     => 'auth',
                            'action'    => 'menuOrder',
                            'type'      => 1,
                            'status'    => 0
                        ]
                    )
                ],
                [
                    'name'      => '配置属性',
                    'app'       => 'manage',
                    'model'     => 'configure',
                    'action'    => 'default',
                    'type'      => '0',
                    'child'     => array(
                        [
                            'name'      => '配置属性列表',
                            'app'       => 'manage',
                            'model'     => 'configure',
                            'action'    => 'index',
                            'type'      => 1,
                            'status'    => 1
                        ],
                        [
                            'name'      => '属性添加',
                            'app'       => 'manage',
                            'model'     => 'configure',
                            'action'    => 'add',
                            'type'      => 1,
                            'status'    => 1
                        ],
                        [
                            'name'      => '属性修改',
                            'app'       => 'manage',
                            'model'     => 'configure',
                            'action'    => 'edit',
                            'type'      => 1,
                            'status'    => 0
                        ],
                        [
                            'name'      => '属性更新',
                            'app'       => 'manage',
                            'model'     => 'configure',
                            'action'    => 'update',
                            'type'      => 1,
                            'status'    => 0
                        ],
                        [
                            'name'      => '配置删除',
                            'app'       => 'manage',
                            'model'     => 'configure',
                            'action'    => 'delete',
                            'type'      => 1,
                            'status'    => 0
                        ]
                    )
                ],
                [
                    'name'      => '模版文件管理',
                    'app'       => 'manage',
                    'model'     => 'template',
                    'action'    => 'default',
                    'type'      => '0',
                    'child'     => array(
                        [
                            'name'      => '模版文件列表',
                            'app'       => 'manage',
                            'model'     => 'template',
                            'action'    => 'index',
                            'type'      => 1,
                            'status'    => 1
                        ],
                        [
                            'name'      => '模版文件添加',
                            'app'       => 'manage',
                            'model'     => 'template',
                            'action'    => 'add',
                            'type'      => 1,
                            'status'    => 1
                        ],
                        [
                            'name'      => '模版文件修改',
                            'app'       => 'manage',
                            'model'     => 'template',
                            'action'    => 'edit',
                            'type'      => 1,
                            'status'    => 0
                        ],
                        [
                            'name'      => '模版文件更新',
                            'app'       => 'manage',
                            'model'     => 'template',
                            'action'    => 'update',
                            'type'      => 1,
                            'status'    => 0
                        ]
                    )
                ],
                [
                    'name'      => '前端分类管理',
                    'app'       => 'manage',
                    'model'     => 'category',
                    'action'    => 'default',
                    'type'      => 1,
                    'status'    => 1,
                    'child'     => array(
                        [
                            'name'      => '分类列表',
                            'app'       => 'manage',
                            'model'     => 'category',
                            'action'    => 'index',
                            'type'      => 1,
                            'status'    => 1
                        ],
                        [
                            'name'      => '分类增加',
                            'app'       => 'manage',
                            'model'     => 'category',
                            'action'    => 'add',
                            'type'      => 1,
                            'status'    => 1
                        ],
                        [
                            'name'      => '分类修改',
                            'app'       => 'manage',
                            'model'     => 'category',
                            'action'    => 'edit',
                            'type'      => 1,
                            'status'    => 0
                        ],
                        [
                            'name'      => '分类删除',
                            'app'       => 'manage',
                            'model'     => 'category',
                            'action'    => 'delete',
                            'type'      => 1,
                            'status'    => 0
                        ],
                        [
                            'name'      => '分类更新',
                            'app'       => 'manage',
                            'model'     => 'category',
                            'action'    => 'update',
                            'type'      => 1,
                            'status'    => 0
                        ],
                        [
                            'name'      => '分类排序',
                            'app'       => 'manage',
                            'model'     => 'category',
                            'action'    => 'sort',
                            'type'      => 1,
                            'status'    => 0
                        ]
                    )
                ],
                [
                    'name'      => '数据扩展',
                    'app'       => 'manage',
                    'model'     => 'extended',
                    'action'    => 'default',
                    'type'      => '0',
                    'child'     => array(
                        [
                            'name'      => '数据扩展列表',
                            'app'       => 'manage',
                            'model'     => 'extended',
                            'action'    => 'index',
                            'type'      => 1,
                            'status'    => 1

                        ],
                        [
                            'name'      => '数据扩展增加',
                            'app'       => 'manage',
                            'model'     => 'extended',
                            'action'    => 'index',
                            'type'      => 1,
                            'status'    => 1
                        ],
                        [
                            'name'      => '更新扩展',
                            'app'       => 'manage',
                            'model'     => 'extended',
                            'action'    => 'update',
                            'type'      => 1,
                            'status'    => 0
                        ],
                        [
                            'name'      => '删除扩展',
                            'app'       => 'manage',
                            'model'     => 'extended',
                            'action'    => 'delete',
                            'type'      => 1,
                            'status'    => 0
                        ],
                        [
                            'name'      => '数据类型修改',
                            'app'       => 'manage',
                            'model'     => 'extended',
                            'action'    => 'dataTypeEdit',
                            'type'      => 1,
                            'status'    => 0
                        ],
                        [
                            'name'      => '字段类型修改',
                            'app'       => 'manage',
                            'model'     => 'extended',
                            'action'    => 'fieldsTypeEdit',
                            'type'      => 1,
                            'status'    => 0
                        ],
                        [
                            'name'      => '删除数据库字段',
                            'app'       => 'manage',
                            'model'     => 'extended',
                            'action'    => 'mysqlFieldsDelete',
                            'type'      => 1,
                            'status'    => 0
                        ]
                    )
            ]
            )
        ];
        return $arr;
    }

    /**
     * 文章管理
     */
    public function menuArticle()
    {
        $arr = [
            'name'      => '内容管理',
            'app'       => 'manage',
            'model'     => 'article',
            'action'    => 'default',
            'type'      => '0',
            'child'     => array(
                [
                    'name'      => '信息管理',
                    'app'       => 'manage',
                    'model'     => 'info',
                    'action'    => 'default',
                    'type'      => '0',
                    'child'     => array(
                        [
                            'name'      => '信息列表',
                            'app'       => 'manage',
                            'model'     => 'info',
                            'action'    => 'index',
                            'type'      => 1,
                            'status'    => 1
                        ],
                        [
                            'name'      => '信息增加',
                            'app'       => 'manage',
                            'model'     => 'info',
                            'action'    => 'add',
                            'type'      => 1,
                            'status'    => 1
                        ],
                        [
                            'name'      => '信息创建',
                            'app'       => 'manage',
                            'model'     => 'info',
                            'action'    => 'create',
                            'type'      => 1,
                            'status'    => 0
                        ],
                        [
                            'name'      => '信息编辑',
                            'app'       => 'manage',
                            'model'     => 'info',
                            'action'    => 'edit',
                            'type'      => 1,
                            'status'    => 0
                        ],
                        [
                            'name'      => '信息更新',
                            'app'       => 'manage',
                            'model'     => 'info',
                            'action'    => 'update',
                            'type'      => 1,
                            'status'    => 0
                        ],
                        [
                            'name'      => '信息删除',
                            'app'       => 'manage',
                            'model'     => 'info',
                            'action'    => 'delete',
                            'type'      => 1,
                            'status'    => 0
                        ],
                        [
                            'name'      => '信息排序',
                            'app'       => 'manage',
                            'model'     => 'info',
                            'action'    => 'sort',
                            'type'      => 1,
                            'status'    => 0
                        ]
                    )
                ],[
                    'name'      => '导航目录',
                    'app'       => 'manage',
                    'model'     => 'category',
                    'action'    => 'default',
                    'type'      => 1,
                    'status'    => 1
                ]
            )
        ];
        return $arr;
    }

    /**
     * 其他管理
     */
    public function menuOther(){
        $arr = [
            'name'      => '其他管理',
            'app'       => 'manage',
            'model'     => 'other',
            'action'    => 'default',
            'type'      => '0',
            'child'     => array(
                [
                    'name'      => '幻灯片管理',
                    'app'       => 'manage',
                    'model'     => 'banner',
                    'action'    => 'default',
                    'type'      => '0',
                    'child'     => array(
                        [
                            'name'      => '幻灯片列表',
                            'app'       => 'manage',
                            'model'     => 'banner',
                            'action'    => 'index',
                            'type'      => 1,
                            'status'    => 1
                        ],
                        [
                            'name'      => '幻灯片增加',
                            'app'       => 'manage',
                            'model'     => 'banner',
                            'action'    => 'add',
                            'type'      => 1,
                            'status'    => 1
                        ],
                        [
                            'name'      => '幻灯片修改',
                            'app'       => 'manage',
                            'model'     => 'banner',
                            'action'    => 'edit',
                            'type'      => 1,
                            'status'    => 0
                        ],
                        [
                            'name'      => '幻灯片更新',
                            'app'       => 'manage',
                            'model'     => 'banner',
                            'action'    => 'update',
                            'type'      => 1,
                            'status'    => 0
                        ],
                        [
                            'name'      => '幻灯片删除',
                            'app'       => 'manage',
                            'model'     => 'banner',
                            'action'    => 'delete',
                            'type'      => 1,
                            'status'    => 0
                        ]
                    )
                ],
                [
                    'name'      => '信息碎片管理',
                    'app'       => 'manage',
                    'model'     => 'fragment',
                    'action'    => 'default',
                    'type'      => '0',
                    'child'     => array(
                        [
                            'name'      => '碎片列表',
                            'app'       => 'manage',
                            'model'     => 'fragment',
                            'action'    => 'index',
                            'type'      => 1,
                            'status'    => 1
                        ],
                        [
                            'name'      => '碎片增加',
                            'app'       => 'manage',
                            'model'     => 'fragment',
                            'action'    => 'add',
                            'type'      => 1,
                            'status'    => 1
                        ],
                        [
                            'name'      => '碎片修改',
                            'app'       => 'manage',
                            'model'     => 'fragment',
                            'action'    => 'edit',
                            'type'      => 1,
                            'status'    => 0
                        ],
                        [
                            'name'      => '碎片更新',
                            'app'       => 'manage',
                            'model'     => 'fragment',
                            'action'    => 'update',
                            'type'      => 1,
                            'status'    => 0
                        ],
                        [
                            'name'      => '碎片删除',
                            'app'       => 'manage',
                            'model'     => 'fragment',
                            'action'    => 'delete',
                            'type'      => 1,
                            'status'    => 0
                        ]
                    )
                ],[
                    'name'      => '文件管理',
                    'app'       => 'manage',
                    'model'     => 'file',
                    'action'    => 'default',
                    'type'      => 0,
                    'status'    => 1,
                    'child'     => array(
                        [
                            'name'      => '文件列表',
                            'app'       => 'manage',
                            'model'     => 'file',
                            'action'    => 'index',
                            'type'      => 1,
                            'status'    => 1
                        ]
                    )
                ]
            )
        ];
        return $arr;
    }

}