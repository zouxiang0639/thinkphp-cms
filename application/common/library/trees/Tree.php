<?php
namespace app\common\library\trees;

use Countable;
use Closure;
use think\Collection;

class Tree implements Countable
{

    protected $items = array();
    protected $parentField = '';
    public $icon    = array('│', '├', '└');

    public function __construct($parentField)
    {
        $this->parentField = $parentField;
    }

    /**
     * 创建一个树型数据.
     *
     * @param Collection  $date
     * @param Closure $resolver
     *
     * @return Tree
     */
    public function create(Collection $date, Closure $resolver)
    {
        $this->items = $this->recursion($date);
        return $resolver($this);
    }

    /**
     * 多维树型数据转化一维树型.
     * @return Tree
     */
    public function toLinearArray()
    {
        $date = $this->items;
        $this->items = [];
        $this->TransformLinearArray($date);

        return $this;
    }

    /**
     * 获取Items数据.
     * @return array
     */
    public function getItems()
    {
      return $this->items;
    }

    /**
     * 统计
     * @return int
     */
    public function count()
    {
        return count($this->items);
    }

    /**
     * 数据递归 重组树行数据
     * @param $date array
     * @param $parent  int   父级
     * @param $level   int   所在级别
     * @return int
     */
    public function recursion($date, $parent = 0, $level = 0)
    {
        $array = array();
        foreach($date as $value) {

            if($value[$this->parentField] == $parent){
                $value->icon = $this->setIcon($level) ;
                $value->level = $level;
                $value->child = $this->recursion($date, $value[$value->primaryKey], $level + 1);
                $array[] = $value;
            }
        }
        return $array;
    }

    /**
     * 多维树型数据递归转化一维
     * @param $date array
     * @return int
     */
    private function TransformLinearArray($date){
        foreach ($date as $value) {
            $child = $value->child;
            unset($value->child);
            array_push($this->items, $value);
            if(count($child) > 0) {
                $this->TransformLinearArray($child);
            }
        }
    }

    /**
     * 拼接等级标签
     * @param $level int  所在级别
     * @return string
     */
    private function setIcon($level)
    {
        $icon = '';
        for ($i = 0; $i<=$level; $i++) {
            if($i == $level){
                $icon .= $this->icon[1];
            }else{
                $icon .= $this->icon[0];
            }
        }

        return $icon;
    }
}