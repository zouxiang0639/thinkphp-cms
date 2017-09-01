<?php
namespace app\common\bls\forms;

use app\common\bls\file\model\FileModel;
use app\common\bls\forms\model\FormsModel;

class FormsBls
{

    public static function getFormsList($where = '', $limit = 18)
    {
        return FormsModel::where($where)->paginate($limit, '' , [
            'query' => input()
        ]);
    }

    public static function getOneForm($where)
    {
        return FormsModel::where($where)->find();
    }

    public static function getFormSelect($where, $order = '')
    {
        return FormsModel::where($where)->order($order)->select();
    }

    public static function createForms($extended_id, $extend, $user_id = 0)
    {
        $model = new FormsModel();
        $model->extended_id = $extended_id;
        $model->user_id = $user_id;
        $model->extend = $extend;

        return $model->save();

    }
}