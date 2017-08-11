<?php
namespace app\common\bls\page\model;

use app\common\base\BasicModel;

class PageModel extends BasicModel
{
    public $name = 'page';
    public $primaryKey = 'page_id';

    //关联一对一
    public function dataFieldsExtended()
    {
        return $this->hasOne('app\common\bls\extended\model\ExtendedModel','extended_id','data_extended_id');
    }

}