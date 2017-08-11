<?php
namespace app\common\bls\category;

use app\common\bls\category\model\CategoryModel;
use app\common\library\trees\Tree;
use think\model\Collection;

class CategoryBls
{

    /**
     * 获取导航列表
     *
     * @param string $where
     * @param int $limit
     * @return Collection
     */
    public static function getCategoryList($where = '', $limit = 20)
    {
        return CategoryModel::where($where)
        ->order(["sort" => "asc"])
        ->paginate($limit, '', [
            'query' => input()
        ]);
    }

    /**
     * 排序导航
     *
     * @param $date
     * @param int $parent_id
     * @return bool
     */
    public static function categorySort($date, $parent_id = 0)
    {
        foreach($date as $values) {
            static $num = 0;

            foreach ($date as $value) {
                $num ++;
                CategoryModel::where('category_id', $value['id'])->update(['sort'=> $num,'parent_id' => $parent_id]);
                if(isset($value['children'])) {
                    self::navigateSort($value['children'], $value['id']);
                }
            }

            return true;
        }
    }

    /**
     * @param string $where
     * @return CategoryModel
     */
    public static function getOneCategory($where = '')
    {
        return CategoryModel::where($where)->find();
    }

    /**
     * 获取全部的导航
     * @param array $where
     * @return Collection
     */
    public static function getAllCategory($where = [])
    {
        return CategoryModel::where($where)->order('sort', 'asc')->select();
    }

    /**
     * @param $group
     * @return Tree
     */
    public static function getTreeCategory($group)
    {
        $model = self::getAllCategory(['group' => $group]);

        return  (new Tree('parent_id'))->create($model, function($object){
            $array = array();
            $items = $object->toLinearArray()->getItems();
            foreach ($items as $value) {
                $array[$value->category_id] = $value->icon.$value->title;
            }
            return $array;
        });

    }

    public static function createCategory($date)
    {
        return CategoryModel::create($date);
    }

    public static function updateCategory($id, $date)
    {
        return CategoryModel::where('category_id',$id)->save($date);
    }

}