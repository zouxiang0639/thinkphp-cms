<?php
namespace app\common\model;

use app\common\tool\Tool;
use app\manage\controller\Template;
use thinkcms\auth\library\Tree;
use thinkcms\auth\model\Menu;

class CategoryModel extends BasicModel
{
    public $name = 'category';

    //字段转型
    protected $type = [
        'extend' => 'array'
    ];

    //关联一对一 后台菜单模型
    public function menu()
    {
        return $this->hasOne('thinkcms\auth\model\Menu','nav_id','category_id');
    }

    //关联一对多 扩展字段
    public function extended()
    {
        return $this->hasMany('app\common\model\ExtendedModel','parent_id','extended_id');
    }

    //关联一对多 后台信息
    public function info()
    {
        return $this->hasMany('app\common\model\InfoModel','category_id','category_id');
    }

    /**
     * data_extended_id读取器
     * 如果本级data_extended_id=0 就获取上级data_extended_id
     *
     * @param  int      $value
     * @param  array   $data
     * @return int
     */
    protected function getDataExtendedIdAttr($value, $data)
    {
        if($value){
            return $value;
        }else if(!empty($data['parent_id'])){
            return CategoryModel::where(['category_id'=>$data['parent_id']])->value('data_extended_id');
        }
        return '';
    }
    /**
     * fields_extended_id读取器
     * 如果本级fields_extended_id=0 就获取上级fields_extended_id
     *
     * @param  int      $value
     * @param  array   $data
     * @return int
     */
    protected function getFieldsExtendedIdAttr($value, $data)
    {
        if($value){
            return $value;
        }else if(empty($data['parent_id'])){
            return CategoryModel::where(['category_id'=>$data['parent_id']])->value('fields_extended_id');
        }
        return '';
    }

    /**
     * 树形导航option form 生成
     *
     * @param  int      $selected
     * @param  string   $terminal
     * @return string
     */
    public static function treeCategory($selected = 1){

        $query = self::where('')
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

    /**
     * 保存当前数据对象
     * @access public
     * @param array  $data     数据
     * @param array  $where    更新条件
     * @param string $sequence 自增序列名
     * @return integer|false
     */
    public function save($data = [], $where = [], $sequence = null)
    {
        //层级关系
        if(!empty($data['parent_id'])){
            $quest          = $this->get($data['parent_id']);
            $data['path']   = "{$quest['path']},{$quest['category_id']}";
        }

        $category = parent::save($data, $where, $sequence);

        //生成后台菜单数据
        if(array_get($data, 'template_group')){
            $builderMenu    = lang('template builder menu');
            $builderMenu    = $builderMenu[$data['template_group']];
            $path           =  explode('/', $builderMenu[2]);
            if(empty($data['parent_id'])){
                $menuId     = $builderMenu[1];
            }else{
                $menuId     = Menu::where(['nav_id' => $data['parent_id']])->value('id');
            }
        }

        //判断菜单是否存在,如果存在更新否则创建
        if(!empty($this->menu)){

            //分类父级修改 更新后台父级
            if(!empty($menuId)){
                $this->menu->parent_id  = $menuId;
            }

            //分类模型修改 更新路由
            if(!empty($path)){
                $this->menu->app        = request()->module();
                $this->menu->model      = $path[0];
                $this->menu->action     = $path[1];
            }

            //默认修改成员数据
            $this->menu->name       = $this->title;
            $this->menu->list_order = $this->sort;

            //数据更新
            $this->menu->save();
        }else{

            //关联后台菜单创建
            $menuDate   = [
                'parent_id'     => $menuId,
                'status'        => 1,
                'type'          => 1,
                'name'          => $this->title,
                'nav_id'        => $this->category_id,
                'app'           => request()->module(),
                'model'         => $path[0],
                'action'        => $path[1],
                'url_param'     => "id={$this->category_id}",
                'rule_param'    => "{id}=={$this->category_id}",
            ];

            //创建后台菜单目录
            Menu::create($menuDate);
        }


        return $category;

    }

    /**
     * 删除当前的记录
     * @access public
     * @return integer
     */
    public function delete()
    {
       $result = parent::delete();
       if($result && !empty($this->menu)){
           $this->menu->delete();
       };
       return $result;
    }
}