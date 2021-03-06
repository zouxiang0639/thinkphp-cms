<?php
namespace app\common\bls\category\model;



use app\common\base\BasicModel;

class CategoryModel extends BasicModel
{
    // 设置完整的数据表（包含前缀）
    protected $name         = 'category';
    public $primaryKey      = 'category_id';


    /**
     * 统计子集数量。
     */
    public function subsetNum()
    {
        $count = self::where('parent_id', $this->category_id)->count();
        return $count;
    }

    //关联一对一 分类
    public function page()
    {
        return $this->hasOne('app\common\bls\page\model\PageModel','page_id', 'page_id');
    }

}