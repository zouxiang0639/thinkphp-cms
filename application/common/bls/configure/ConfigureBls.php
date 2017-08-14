<?php
namespace app\common\bls\configure;

use app\common\bls\configure\model\ConfigureModel;
use app\common\tool\Tool;
use think\Config;

class ConfigureBls
{
    public static function getConfigureList($where = '', $limit = 20)
    {
        return ConfigureModel::where($where)->paginate($limit, '', [
            'query' => input()
        ]);
    }

    public static function getConfigureSelect($where = '')
    {
        return ConfigureModel::where($where)->Select();
    }

    public static function createConfigure($data)
    {
        $model = new ConfigureModel();
        $model->title = $data['title'];
        $model->type = $data['type'];
        $model->configure_name = $data['configure_name'];
        $model->input_type = $data['input_type'];
        $model->comment = $data['comment'];
        $model->configure_value = $data['configure_value'];

        return $model->save();
    }

    /**
     * @param string $where
     * @return ConfigureModel
     */
    public static function getOneConfigure($where = '')
    {
        return ConfigureModel::where($where)->find();
    }

    /**
     * 配置生成input
     *
     * @param  object  $list
     * @param  string  $name
     * @return string
     */
    public static function htmlBuilder($list, $name)
    {
        $html       = '';

        //循环每个配置变量 生成html
        foreach($list as $v){

            //使用表单枚举生成<form> 标签支持
            $input  =  Tool::get('helper')->formEnum(
                $v['input_type'],          //表单类型
                $v['configure_name'],                           //配置变量名称
                Config::get($name.'.'.$v['configure_name']),    //读取配置变量的值
                ['class' => 'form-control text'],               //其他属性
                json_decode($v['configure_value'])              //需要生成多个 如select
            );

            //写入$html变量里
            $html   .= "<tr>
                            <th width='100'>{$v['title']}</th>
                            <th>
                                {$input}<span style='padding-left: 10px'>{$v['comment']}</span>
                            </th>
                        </tr>";
        }

        return $html;
    }
}