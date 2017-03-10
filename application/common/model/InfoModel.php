<?php
namespace app\common\model;

class InfoModel extends BasicModel
{
    public $name = 'info';

    //关联一对一 分类
    public function category()
    {
        return $this->hasOne('categoryModel','category_id','category_id')
            ->bind([
            'category_title'         => 'title',
            ])
            ->setEagerlyType(0);
    }

    // recommendation读取器
    protected function getRecommendationAttr($value)
    {
        return explode(',', $value);
    }

    // recommendation修改器
    protected function setRecommendationAttr($value)
    {
        return implode(',', $value);
    }

    // picture修改器
    protected function getPictureAttr($value)
    {
      return parent::defaultImage($value);
    }


}