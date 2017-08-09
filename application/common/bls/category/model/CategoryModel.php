<?php
namespace app\common\bls\category\model;


use app\common\model\BasicModel;

class CategoryModel extends BasicModel
{
    // 设置完整的数据表（包含前缀）
    protected $name         = 'navigate';
    public $primaryKey      = 'navigate_id';


    /**
     * 统计子集数量。
     */
    public function subsetNum()
    {
        $count = self::where('parent_id', $this->navigate_id)->count();
        return $count;
    }

}