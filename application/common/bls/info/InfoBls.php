<?php
namespace app\common\bls\info;

use app\common\bls\info\model\InfoModel;

class InfoBls
{
    public static function getInfoList($where = '', $limit = 20)
    {
        return InfoModel::where($where)
            ->order(["sort" => "desc", 'info_id' => 'desc'])
            ->paginate($limit, '', [
                'query' => input()
            ]);
    }

    public static function createInfo($date)
    {
        $model = new InfoModel();
        $model->title       = $date['title'];
        $model->page_id     = $date['page_id'];
        $model->display     = $date['display'];
        $model->links       = $date['links'];
        $model->visiting    = $date['visiting'];
        $model->comment     = $date['comment'];
        $model->photos      = $date['photos'];
        $model->picture     = $date['picture'];
        $model->create_time = $date['create_time'];
        $model->keywords    = $date['keywords'];
        $model->description = $date['description'];
        $model->content     = $date['content'];

        return $model->save();
    }
}