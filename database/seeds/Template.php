<?php

use think\migration\Seeder;

class Template extends Seeder
{
    public $data = [];

    /**
     * Run Method.
     */
    public function run()
    {
        $array = [
            $this->Category(),
            $this->info()
        ];

        foreach($array as $v){
            $this->recursion($v);
        }

        $table = $this->table('template');
        $table->insert($this->data);
        $table->saveData();
    }

    /**
     * 递归
     */
    public function recursion($data)
    {
        foreach($data as $v){
            $this->factory($v);
        }
    }

    /**
     * 工厂
     *
     * type 1:分类页面, 2:信息内页, 3:通用页面 具体按照语言包 lang('template type')
     * group 1:单页模型, 2:信息模型 具体按照语言包 lang('template group')
     */
    public function factory($date)
    {
        $add[] = [
            'title'             => array_get($date, 'title', ''),
            'type'              => array_get($date, 'type', 1),
            'group'             => array_get($date, 'group', 1),
            'template_file'     => array_get($date, 'template_file', ''),
        ];

        $this->data = array_merge($this->data, $add);
    }

    /**
     * 分类页面
     */
    public function Category($groups = 1)
    {
        $array = [
            [
                'title'             => '单页模版',
                'type'              => 1,
                'group'            => $groups,
                'template_file'     => 'article',
            ]
        ];

        return $array;
    }

    /**
     * 信息内页
     */
    public function info($groups = 2)
    {
        $array = [
            [
                'title'             => '信息内页模版',
                'type'              => 2,
                'group'            => $groups,
                'template_file'     => 'info',
            ]
        ];
        return $array;
    }
}