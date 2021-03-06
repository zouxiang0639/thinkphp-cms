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
           $this->menuExtendedTool(),
           $this->menuUser(),
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
                    'name'      => '标签管理',
                    'app'       => 'manage',
                    'model'     => 'label',
                    'action'    => 'default',
                    'type'      => '0',
                    'child'     => array(
                        [
                            'name'      => '标签列表',
                            'app'       => 'manage',
                            'model'     => 'label',
                            'action'    => 'index',
                            'type'      => 1,
                            'status'    => 1
                        ],
                        [
                            'name'      => '标签添加',
                            'app'       => 'manage',
                            'model'     => 'label',
                            'action'    => 'add',
                            'type'      => 1,
                            'status'    => 1
                        ],
                        [
                            'name'      => '标签修改',
                            'app'       => 'manage',
                            'model'     => 'label',
                            'action'    => 'edit',
                            'type'      => 1,
                            'status'    => 0
                        ],
                        [
                            'name'      => '标签更新',
                            'app'       => 'manage',
                            'model'     => 'label',
                            'action'    => 'update',
                            'type'      => 1,
                            'status'    => 0
                        ],
                        [
                            'name'      => '标签删除',
                            'app'       => 'manage',
                            'model'     => 'label',
                            'action'    => 'delete',
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
            ],
                [
                    'name'      => '数据库管理',
                    'app'       => 'manage',
                    'model'     => 'backups',
                    'action'    => 'default',
                    'type'      => '0',
                    'child'     => array(
                        [
                            'name'      => '数据库列表',
                            'app'       => 'manage',
                            'model'     => 'backups',
                            'action'    => 'index',
                            'type'      => 1,
                            'status'    => 1

                        ],
                        [
                            'name'      => '数据库还原',
                            'app'       => 'manage',
                            'model'     => 'backups',
                            'action'    => 'import',
                            'type'      => 1,
                            'status'    => 1
                        ],
                        [
                            'name'      => '数据库备份',
                            'app'       => 'manage',
                            'model'     => 'backups',
                            'action'    => 'export',
                            'type'      => 1,
                            'status'    => 0
                        ],
                        [
                            'name'      => '数据库优化',
                            'app'       => 'manage',
                            'model'     => 'backups',
                            'action'    => 'optimize',
                            'type'      => 1,
                            'status'    => 0
                        ],
                        [
                            'name'      => '数据表修复',
                            'app'       => 'manage',
                            'model'     => 'backups',
                            'action'    => 'repair',
                            'type'      => 1,
                            'status'    => 0
                        ],
                        [
                            'name'      => '还原数据库',
                            'app'       => 'manage',
                            'model'     => 'backups',
                            'action'    => 'restore',
                            'type'      => 1,
                            'status'    => 0
                        ],
                        [
                            'name'      => '删除备份文件',
                            'app'       => 'manage',
                            'model'     => 'backups',
                            'action'    => 'delete',
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
        $subProduct = [
            'name'      => '附属产品管理',
            'app'       => 'manage',
            'model'     => 'goods',
            'action'    => 'default',
            'type'      => '0',
            'status'    => 0,
            'child'     => array(
                [
                    'name'      => '附属产品列表',
                    'app'       => 'manage',
                    'model'     => 'goods',
                    'action'    => 'subproduct',
                    'type'      => 1,
                    'status'    => 0
                ],
                [
                    'name'      => '附属产品增加',
                    'app'       => 'manage',
                    'model'     => 'goods',
                    'action'    => 'subproductAdd',
                    'type'      => 1,
                    'status'    => 0
                ],
                [
                    'name'      => '附属产品编辑',
                    'app'       => 'manage',
                    'model'     => 'goods',
                    'action'    => 'subproductEdit',
                    'type'      => 1,
                    'status'    => 0
                ],
                [
                    'name'      => '附属产品更新',
                    'app'       => 'manage',
                    'model'     => 'goods',
                    'action'    => 'subproductUpdate',
                    'type'      => 1,
                    'status'    => 0
                ],
                [
                    'name'      => '附属产品删除',
                    'app'       => 'manage',
                    'model'     => 'goods',
                    'action'    => 'subproductDelete',
                    'type'      => 1,
                    'status'    => 0
                ]
            )
        ];

        $GoodsExportExcel = [
                'name'      => '导出Excel',
                'app'       => 'manage',
                'model'     => 'goods',
                'action'    => 'exportExcel',
                'type'      => 1,
                'status'    => 0
            ];

        $goodsLoadExcel = [
            'name'      => '导入Excel',
            'app'       => 'manage',
            'model'     => 'goods',
            'action'    => 'loadExcel',
            'type'      => 1,
            'status'    => 0
        ];

        $arr = [
            'name'      => '内容管理',
            'app'       => 'manage',
            'model'     => 'article',
            'action'    => 'default',
            'type'      => '0',
            'child'     => array(
                [
                    'name'      => '页面管理',
                    'app'       => 'manage',
                    'model'     => 'page',
                    'action'    => 'default',
                    'type'      => 1,
                    'status'    => 1,
                    'child'     => array(
                        [
                            'name'      => '页面列表',
                            'app'       => 'manage',
                            'model'     => 'page',
                            'action'    => 'index',
                            'type'      => 1,
                            'status'    => 1
                        ],
                        [
                            'name'      => '页面增加',
                            'app'       => 'manage',
                            'model'     => 'page',
                            'action'    => 'add',
                            'type'      => 1,
                            'status'    => 1
                        ],
                        [
                            'name'      => '页面创建',
                            'app'       => 'manage',
                            'model'     => 'page',
                            'action'    => 'create',
                            'type'      => 1,
                            'status'    => 0
                        ],
                        [
                            'name'      => '页面修改',
                            'app'       => 'manage',
                            'model'     => 'page',
                            'action'    => 'edit',
                            'type'      => 1,
                            'status'    => 0
                        ],
                        [
                            'name'      => '页面删除',
                            'app'       => 'manage',
                            'model'     => 'page',
                            'action'    => 'delete',
                            'type'      => 1,
                            'status'    => 0
                        ],
                        [
                            'name'      => '页面更新',
                            'app'       => 'manage',
                            'model'     => 'page',
                            'action'    => 'update',
                            'type'      => 1,
                            'status'    => 0
                        ]
                    )
                ],
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
                ],
                [
                    'name'      => '产品管理',
                    'app'       => 'manage',
                    'model'     => 'goods',
                    'action'    => 'default',
                    'type'      => '0',
                    'child'     => array(
                        [
                            'name'      => '产品列表',
                            'app'       => 'manage',
                            'model'     => 'goods',
                            'action'    => 'index',
                            'type'      => 1,
                            'status'    => 1
                        ],
                        [
                            'name'      => '产品增加',
                            'app'       => 'manage',
                            'model'     => 'goods',
                            'action'    => 'add',
                            'type'      => 1,
                            'status'    => 1
                        ],
                        [
                            'name'      => '产品创建',
                            'app'       => 'manage',
                            'model'     => 'goods',
                            'action'    => 'create',
                            'type'      => 1,
                            'status'    => 0
                        ],
                        [
                            'name'      => '产品编辑',
                            'app'       => 'manage',
                            'model'     => 'goods',
                            'action'    => 'edit',
                            'type'      => 1,
                            'status'    => 0
                        ],
                        [
                            'name'      => '产品更新',
                            'app'       => 'manage',
                            'model'     => 'goods',
                            'action'    => 'update',
                            'type'      => 1,
                            'status'    => 0
                        ],
                        [
                            'name'      => '产品删除',
                            'app'       => 'manage',
                            'model'     => 'goods',
                            'action'    => 'delete',
                            'type'      => 1,
                            'status'    => 0
                        ],
                        [
                            'name'      => '产品排序',
                            'app'       => 'manage',
                            'model'     => 'goods',
                            'action'    => 'sort',
                            'type'      => 1,
                            'status'    => 0
                        ]
                    )
                ],
                [
                    'name'      => '导航管理',
                    'app'       => 'manage',
                    'model'     => 'category',
                    'action'    => 'default',
                    'type'      => 1,
                    'status'    => 1,
                    'child'     => array(
                        [
                            'name'      => '导航列表',
                            'app'       => 'manage',
                            'model'     => 'category',
                            'action'    => 'index',
                            'type'      => 1,
                            'status'    => 1
                        ],
                        [
                            'name'      => '导航增加',
                            'app'       => 'manage',
                            'model'     => 'category',
                            'action'    => 'add',
                            'type'      => 1,
                            'status'    => 1
                        ],
                        [
                            'name'      => '导航创建',
                            'app'       => 'manage',
                            'model'     => 'category',
                            'action'    => 'create',
                            'type'      => 1,
                            'status'    => 0
                        ],
                        [
                            'name'      => '导航修改',
                            'app'       => 'manage',
                            'model'     => 'category',
                            'action'    => 'edit',
                            'type'      => 1,
                            'status'    => 0
                        ],
                        [
                            'name'      => '导航删除',
                            'app'       => 'manage',
                            'model'     => 'category',
                            'action'    => 'delete',
                            'type'      => 1,
                            'status'    => 0
                        ],
                        [
                            'name'      => '导航更新',
                            'app'       => 'manage',
                            'model'     => 'category',
                            'action'    => 'update',
                            'type'      => 1,
                            'status'    => 0
                        ],
                        [
                            'name'      => '导航排序',
                            'app'       => 'manage',
                            'model'     => 'category',
                            'action'    => 'sort',
                            'type'      => 1,
                            'status'    => 0
                        ],
                        [
                            'name'      => '生成公众号导航',
                            'app'       => 'manage',
                            'model'     => 'category',
                            'action'    => 'weChatMenu',
                            'type'      => 1,
                            'status'    => 0
                        ]
                    )
                ]
            )
        ];

        //产品导出导入
        if(config('extend.excel_goods')) {
            array_push($arr['child'][2]['child'], $GoodsExportExcel);
            array_push($arr['child'][2]['child'], $goodsLoadExcel);
        }

        //产品附属产品
        if(config('extend.sub_product')) {
            array_push($arr['child'][2]['child'], $subProduct);
        }

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
                ],
                [
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
                        ],
                        [
                            'name'      => '文件上传',
                            'app'       => 'manage',
                            'model'     => 'file',
                            'action'    => 'uploadFile',
                            'type'      => 1,
                            'status'    => 0
                        ],
                    )
                ],
                [
                    'name'      => '表单管理',
                    'app'       => 'manage',
                    'model'     => 'forms',
                    'action'    => 'default',
                    'type'      => '0',
                    'child'     => array(
                        [
                            'name'      => '表单列表',
                            'app'       => 'manage',
                            'model'     => 'forms',
                            'action'    => 'index',
                            'type'      => 1,
                            'status'    => 1
                        ],
                        [
                            'name'      => '表单详细',
                            'app'       => 'manage',
                            'model'     => 'forms',
                            'action'    => 'show',
                            'type'      => 1,
                            'status'    => 0
                        ],
                        [
                            'name'      => '删除',
                            'app'       => 'manage',
                            'model'     => 'forms',
                            'action'    => 'delete',
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
     * 会员管理
     */
    public function menuUser()
    {
        //积分规则管理
        $integral = [
            'name'      => '积分规则',
            'app'       => 'manage',
            'model'     => 'user.integral.rule',
            'action'    => 'default',
            'type'      => '0',
            'child'     => array(
                [
                    'name'      => '规则列表',
                    'app'       => 'manage',
                    'model'     => 'user.integral.rule',
                    'action'    => 'index',
                    'type'      => 1,
                    'status'    => 1
                ],
                [
                    'name'      => '规则添加',
                    'app'       => 'manage',
                    'model'     => 'user.integral.rule',
                    'action'    => 'add',
                    'type'      => 1,
                    'status'    => 1
                ],
                [
                    'name'      => '规则修改',
                    'app'       => 'manage',
                    'model'     => 'user.integral.rule',
                    'action'    => 'edit',
                    'type'      => 1,
                    'status'    => 0
                ],
                [
                    'name'      => '规则更新',
                    'app'       => 'manage',
                    'model'     => 'user.integral.rule',
                    'action'    => 'update',
                    'type'      => 1,
                    'status'    => 0
                ],
                [
                    'name'      => '规则删除',
                    'app'       => 'manage',
                    'model'     => 'user.integral.rule',
                    'action'    => 'delete',
                    'type'      => 1,
                    'status'    => 0
                ]
            )
        ];

        //积分商城管理
        $integralGoods = [
            'name'      => '积分产品',
            'app'       => 'manage',
            'model'     => 'user.integral.goods',
            'action'    => 'default',
            'type'      => '0',
            'child'     => array(
                [
                    'name'      => '积分产品列表',
                    'app'       => 'manage',
                    'model'     => 'user.integral.goods',
                    'action'    => 'index',
                    'type'      => 1,
                    'status'    => 1
                ],
                [
                    'name'      => '积分产品添加',
                    'app'       => 'manage',
                    'model'     => 'user.integral.goods',
                    'action'    => 'add',
                    'type'      => 1,
                    'status'    => 1
                ],
                [
                    'name'      => '积分产品修改',
                    'app'       => 'manage',
                    'model'     => 'user.integral.goods',
                    'action'    => 'edit',
                    'type'      => 1,
                    'status'    => 0
                ],
                [
                    'name'      => '积分产品更新',
                    'app'       => 'manage',
                    'model'     => 'user.integral.goods',
                    'action'    => 'update',
                    'type'      => 1,
                    'status'    => 0
                ],
                [
                    'name'      => '积分产品删除',
                    'app'       => 'manage',
                    'model'     => 'user.integral.goods',
                    'action'    => 'delete',
                    'type'      => 1,
                    'status'    => 0
                ]
            )
        ];



        $arr = [
            'name'      => '会员管理',
            'app'       => 'manage',
            'model'     => 'other',
            'action'    => 'default',
            'type'      => '0',
            'child'     => array(
                [
                    'name'      => '用户管理',
                    'app'       => 'manage',
                    'model'     => 'user.index',
                    'action'    => 'default',
                    'type'      => '0',
                    'child'     => array(
                        [
                            'name'      => '用户列表',
                            'app'       => 'manage',
                            'model'     => 'user.index',
                            'action'    => 'index',
                            'type'      => 1,
                            'status'    => 1
                        ],
                        [
                            'name'      => '用户禁用启用',
                            'app'       => 'manage',
                            'model'     => 'user.index',
                            'action'    => 'status',
                            'type'      => 1,
                            'status'    => 0
                        ],
                        [
                            'name'      => '用户详细',
                            'app'       => 'manage',
                            'model'     => 'user.index',
                            'action'    => 'show',
                            'type'      => 1,
                            'status'    => 0
                        ],
                        [
                            'name'      => '重置密码',
                            'app'       => 'manage',
                            'model'     => 'user.index',
                            'action'    => 'setPassword',
                            'type'      => 1,
                            'status'    => 0
                        ],
                        [
                            'name'      => '用户更新',
                            'app'       => 'manage',
                            'model'     => 'user.index',
                            'action'    => 'update',
                            'type'      => 1,
                            'status'    => 0
                        ]
                    )
                ]
            )
        ];

        if(config('extend.integral')) {
            array_push($arr['child'], $integral);
        }

        if(config('extend.integral_goods')) {
            array_push($arr['child'], $integralGoods);
        }
        return $arr;
    }

}