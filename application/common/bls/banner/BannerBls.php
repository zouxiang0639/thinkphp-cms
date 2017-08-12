<?php
namespace app\common\bls\banner;

use app\common\bls\banner\model\BannerModel;

class BannerBls
{

    public static function getBannerList($where = '', $limit = 20)
    {
        return BannerModel::where($where)->paginate($limit, '', [
            'query' => input()
        ]);
    }

    public static function createBanner($data)
    {
        $model = new BannerModel();
        $model->title = $data['title'];
        $model->type = $data['type'];
        $model->picture = $data['picture'];
        $model->links = $data['links'];
        $model->comment = $data['comment'];
        return $model->save();
    }

    /**
     * @param string $where
     * @return BannerModel
     */
    public static function getOneBanner($where = '')
    {
        return BannerModel::where($where)->find();
    }
}