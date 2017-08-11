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


    /**
     * 生成form表单
     * @return mixed
     */
    public static function formBuilder($id, $data='')
    {

        if($id==0){
            return '';
        }

        $html   = '';
        //获取
        $extend     = self::where(['parent_id'=>$id])
            ->order(["sort" => "desc", 'extended_id' => 'asc'])
            ->select();

        foreach((object)$extend as $v){
            //使用表单枚举生成<form> 标签支持
            $input  =  Tool::get('helper')->formEnum(
                $v['input_type'],        //表单类型
                'extend['.$v['name'].']',                       //变量名称
                array_get($data,$v['name']),                    //置变量的值
                ['class' => 'form-control text'],               //其他属性
                json_decode($v['input_value'])                  //需要生成多个 如select
            );

            $html  .= "<tr>
                            <th>{$v['title']}</th>
                            <th>
                                {$input}
                                <span style='padding-left: 10px'>{$v['comment']}</span>
                            </th>
                        </tr>";
        }

        return $html;
    }

    /**
     * 数据扩展组
     * @return mixed
     */
    public static function extendedGroup()
    {
        $extended   = self::where(['parent_id'=>0])->column('extended_id,group,title');
        $default    = [0 => '请选择'];
        $group      = [0 => $default, 1 => $default, 2 => $default];
        foreach((array)$extended as $v){
            $group[$v['group']][$v['extended_id']]  = $v['title'];
            $group[0][$v['extended_id']]            = $v['title'];
        }
        return $group;
    }

}