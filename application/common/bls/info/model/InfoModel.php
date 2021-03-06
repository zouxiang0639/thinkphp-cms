<?php
namespace app\common\bls\info\model;

use app\common\base\BasicModel;

class InfoModel extends BasicModel
{
    public $name = 'info';
    public $primaryKey = 'info_id';

    //字段转型
    protected $type = [
        'extend' => 'array'
    ];

    //关联一对一 分类
    public function page()
    {
        return $this->hasOne('app\common\bls\page\model\PageModel','page_id', 'page_id');
    }

}