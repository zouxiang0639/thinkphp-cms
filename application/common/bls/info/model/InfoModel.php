<?php
namespace app\common\bls\info\model;

use app\common\base\BasicModel;

class InfoModel extends BasicModel
{
    public $name = 'info';
    public $primaryKey = 'info_id';

    //关联一对一 分类
    public function category()
    {
        return $this->hasOne('app\common\bls\page\model\PageModel','category_id');
    }
}