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

    public static function createFile($data)
    {
        $model = new FileModel();
        $model->path  =  $data['path'];
        $model->name  =  $data['name'];
        $model->type =  $data['type'];
        $model->hash  =  $data['hash'];
        $model->save();
    }

    public static function getOneFile($where)
    {
        return FileModel::where($where)->find();
    }

    public static function updateFile($model, $data)
    {
        $model->path  =  $data['path'];
        $model->name  =  $data['name'];
        $model->type =  $data['type'];
        $model->hash  =  $data['hash'];
        $model->save();
    }

    public static function getFileArray($where)
    {
        return FileModel::where($where)->column(['0,path,name']);
    }

}