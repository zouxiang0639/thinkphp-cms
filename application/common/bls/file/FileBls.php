<?php
namespace app\common\bls\file;


use app\common\bls\file\model\FileModel;

class FileBls
{

    public static function getFileList($where = '', $limit = 18)
    {
       return FileModel::where($where)->paginate($limit, '' , [
            'query' => input()
        ]);
    }
}