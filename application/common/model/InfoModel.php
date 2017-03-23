<?php
namespace app\common\model;

class InfoModel extends BasicModel
{
    public $name = 'info';
    public $extendMysqlName;
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

    // picture读取器
    protected function getPictureAttr($value)
    {
      return parent::defaultImage($value);
    }

    // 检查扩展
    public function checkExtended($id = '')
    {
        $extended = CategoryModel::get($this->category_id)->dataFieldsExtended;
        if($extended['group'] == 2){
            $this->extendMysqlName  = $extended['mysql_name'];
        }
        return $this;
    }

    public function save($data = [], $where = [], $sequence = null)
    {
        if($this->extendMysqlName){
            unset($data['extend']);
        }
        return parent::save($data, $where, $sequence);
    }

}