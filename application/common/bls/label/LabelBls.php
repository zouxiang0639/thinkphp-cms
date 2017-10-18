<?php
namespace app\common\bls\label;

use app\common\bls\label\model\LabelModel;

class LabelBls
{

    public static function getLabelList($where = '', $limit = 20)
    {
        return LabelModel::where($where)->paginate($limit, '', [
            'query' => input()
        ]);
    }

    public static function createLabel($data)
    {
        $model = new LabelModel();
        $model->title = $data['title'];
        $model->type = $data['type'];
        $model->icon = $data['icon'];
        $model->alphabet = $data['alphabet'];
        return $model->save();
    }

    /**
     * @param string $where
     * @return BannerModel
     */
    public static function getOneLabel($where = '')
    {
        return LabelModel::where($where)->find();
    }

    public static function getLabelSelect($where, $order = '')
    {
        return LabelModel::where($where)->order($order)->select();
    }


    /**
     * 获取标签数组
     * @param $type
     * @param string $order
     * @return false|\PDOStatement|string|\think\Collection
     */
    public static function getLabelArray($type, $order = '')
    {
        return LabelModel::where('type', $type)->order($order)->column('title', 'label_id');
    }
}