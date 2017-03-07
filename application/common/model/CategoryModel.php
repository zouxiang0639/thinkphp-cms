<?php
namespace app\common\model;

use thinkcms\auth\library\Tree;

class CategoryModel extends BasicModel
{
    public $name = 'category';


    /**
     * 树形导航option form 生成
     *
     * @param  int      $selected
     * @param  string   $terminal
     * @return string
     */
    public static function treeCategory($selected = 1, $terminal = '电脑端'){

        $query = self::where(['terminal'=>$terminal])
            ->order(["sort" => "asc", 'category_id'=>'asc'])
            ->column('category_id, title, parent_id','category_id');

        $tree       = new Tree();

        //分类树形处理
        foreach ($query as $k => $v) {
            $query[$k]['selected'] = $v['category_id'] == $selected ? 'selected' : '';
        }
        $tree->init($query);
        unset($result); //释放内存

        //树形菜单html生成
        $str = "<option value='\$category_id' \$selected>\$spacer \$title</option>";
        return $tree->get_tree(0, $str);
    }
}