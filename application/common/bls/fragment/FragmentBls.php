<?php
namespace app\common\bls\fragment;



use app\common\bls\fragment\model\FragmentModel;

class FragmentBls
{
    public static function getFragmentList($where = '', $limit = 20)
    {
       return FragmentModel::where($where)->paginate($limit, '', [
           'query' => input()
       ]);
    }

    public static function createFragment($date)
    {
        $model = new FragmentModel();
        $model->title   = $date['title'];
        $model->picture = $date['picture'];
        $model->links   = $date['links'];
        $model->comment = $date['comment'];
        $model->content = $date['content'];
        return $model->save();
    }

    public static function getOneFragment($where)
    {
        return FragmentModel::where($where)->find();
    }

    public static function getFragmentSelect($where, $order = '')
    {
        return FragmentModel::where($where)->order($order)->select();
    }

    public static function getAllFragment()
    {
      return FragmentModel::cache('getAllFragment', 3600)->column('*', 'fragment_id');
    }
}