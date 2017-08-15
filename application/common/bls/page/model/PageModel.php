<?php
namespace app\common\bls\page\model;

use app\common\base\BasicModel;

class PageModel extends BasicModel
{
    public $name = 'page';
    public $primaryKey = 'page_id';

    //字段转型
    protected $type = [
        'extend' => 'array'
    ];

    //关联一对一
    public function extendedData()
    {
        return $this->hasOne('app\common\bls\extended\model\ExtendedModel', 'extended_id', 'data_extended_id');
    }

    public function info()
    {
        return $this->hasMany('app\common\bls\info\model\InfoModel', 'id', 'info_id');
    }

}