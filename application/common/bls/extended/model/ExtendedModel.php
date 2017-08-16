<?php
namespace app\common\bls\extended\model;

use app\common\base\BasicModel;
use app\common\tool\Tool;
use think\Config;
use think\Db;

class ExtendedModel extends BasicModel
{
    protected $name = 'extended';

    /**
     * 获取子数据
     * @return ExtendedModel
     */
    public function extendedChild()
    {
        return $this->hasMany(ExtendedModel::class,'parent_id','extended_id')->order(["sort" => "desc", 'extended_id' => 'asc']);
    }

    /**
     * 删除子集数据
     * @return mixed
     */
    public function deleteChild(){
        return ExtendedModel::where(['parent_id'=>$this->extended_id])->delete();
    }

    public function pageDataExtended()
    {
        return $this->hasMany('app\common\bls\page\model\PageModel', 'data_extended_id', 'extended_id');
    }

    public function pageFieldsExtended()
    {
        return $this->hasMany('app\common\bls\page\model\PageModel', 'fields_extended_id', 'extended_id');
    }
}