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
     * @param $data
     * @param int $parent_id
     * @return bool
     */
    public static function categorySort($data, $parent_id = 0)
    {
        foreach($data as $values) {
            static $num = 0;

            foreach ($data as $value) {
                $num ++;
                $sortDate = [
                    'sort'      => $num,
                    'parent_id' => $parent_id,
                    'path'      => self::categoryPath($parent_id, $value['id'])
                ];
                CategoryModel::where('category_id', $value['id'])->update($sortDate);
                if(isset($value['children'])) {
                    self::categorySort($value['children'], $value['id']);
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
            $array = [0 => '/'];
            $items = $object->toLinearArray()->getItems();
            foreach ($items as $value) {
                $title = $value->title;
                if(empty($title) && $page = $value->page){
                    $title = $page->title;
                }

                $array[$value->category_id] = $value->icon.$title;
            }
            return $array;
        });

    }

    public static function createCategory($data)
    {
        $model = new CategoryModel();
        $model->group = $data['group'];
        $model->title = $data['title'];
        $model->status = $data['status'];
        $model->parent_id = $data['parent_id'];
        $model->bind_page = $data['bind_page'];
        $model->page_id = $data['page_id'];
        $model->links = $data['links'];
        $model->save();
        $model->path = self::categoryPath($model->parent_id, $model->category_id);
        return $model->save();
    }

    public static function updateCategory(CategoryModel $model, $date)
    {
        $date['path'] = self::categoryPath($date['parent_id'], $model->category_id);
        return $model->save($date);
    }

    protected static function categoryPath($parent_id, $category_id)
    {
        if($parent_id == 0) {
            return $category_id;
        }
        $path = self::getOneCategory(['category_id' => $parent_id])->path;
        return $path.','.$category_id;
    }
}