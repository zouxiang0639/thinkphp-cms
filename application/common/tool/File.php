<?php
namespace app\common\tool;

use app\common\bls\file\FileBls;
use app\common\consts\file\FileTypeConst;
use app\common\model\FileModel;

class File extends \think\File
{

    public function __construct($filename, $mode = 'r')
    {
        parent::__construct($filename, $mode);
    }

    /**
     * 数据写入文件
     *
     * @param  string  $filename
     * @param  string  $writetext
     * @param  string  $openmod
     * @return string
     */
    public function writeFile($filename, $writeText, $openmod='w+')
    {
        if(@$fp = fopen($filename, $openmod)){
            flock($fp, 2);
            fwrite($fp, $writeText);
            @chmod($filename,0777);
            fclose($fp);
            return true;
        }else{
            return false;
        }
    }

    /**
     * 重组数组成字符串
     *
     * @param  array  $value
     * @param  mixed  $arr
     * @return string
     */
    public function recombinantArray($value,$arr){
        if(!is_array($arr)){
            (array) $arr = json_decode($arr,true);
        }
        $str = '[';
        foreach($value as $k=>$v){
            $comma  = count($value) -1 == $k ?'':',';
            $str .= '"'.$v.'"=>"'.$arr[$v].'"'.$comma;
        }
        $str .= ']';
        return $str;
    }

    /**
     * 文件上传
     *
     * @param  object   $request
     * @param  string   $arr
     * @param  string   $type
     * @param  int      $id
     * @return array
     */
    public function fileUpload($request, $name, $type, $id = null)
    {

        // 获取表单上传文件
        $file           = $request->file($name);
        $filePath       = false;                            //替换文件的路由
        $hash           = $file->hash();                    //文件hash1 值

        //没有文件返回错误
        if (empty($file)) {
            return $this->result(0, '请选择上传文件');
        }

        /**
         *  图片上传替换原图
         */
        if(!empty($id) && $id !='undefined'){
            $replaceFile = FileBls::getOneFile(['file_id'=>$id]);
            if($replaceFile){
                $filePath  = explode(DS, $replaceFile['path']);
                $count      = count($filePath);
                $filePath  = implode(DS, [$filePath[$count-2], $filePath[$count-1]]);
            }
        }

        /**
         * 如果不是替换文件 根据hash查找文件库是否有上传过
         */
        if(!$filePath){
            $info = FileBls::getFileArray(['hash'=>$hash]);
            //如果查找成功就返回数据库里面的数据
            if(!empty($info)) {
                return $this->result(1, '上传成功', $info[0]['path'], $info[0]['name']);
            }
        }

        /**
         *  图片上传区域
         */
        $fileType   = self::fileType($type); //上传的类型
        $info = $file->validate(['ext' => $fileType['ext']]);

        if($filePath  == false){

            //文件上传
            $info = $info->move(ROOT_PATH . 'public'.$fileType['path']);
        }else{

            //替换文件上传
            $info = $info->move(ROOT_PATH . 'public'.$fileType['path'],$filePath);
        }

        //文件上传后拼接路由
        $base    = request()->root();
        $root    = strpos($base, '.') ? DS.ltrim(dirname($base), DS) : $base;
        $path    = $root.$fileType['path'].$info->saveName;

        /**
         * 生成缩略图
         */
        if($type == 'image') {
            $this->thumbImage($info->filename);
        }

        /**
         * 数据库操作
         */
        if(!$filePath) {
            //创建上传的文件数据
            FileBls::createFile([
                'path'  => $path,
                'name'  => $info->info['name'],
                'type' => FileTypeConst::getNumber($type),
                'hash'  => $hash,
            ]);

        }else if ($filePath && is_object($replaceFile)){

            //更新替换上传的文件数据
            FileBls::updateFile($replaceFile, [
                'path'  => $path,
                'name'  => $info->info['name'],
                'type' => FileTypeConst::getNumber($type),
                'hash'  => $hash,
            ]);
        }

        /**
         * 上传结果返回
         */
        if ($info) {
            // 上传成功
            return $this->result(1, '上传成功', $path, $info->info['name']);
        } else {
            // 上传失败获取错误信息
            return $this->result(0,$file->getError());
        }

    }


    /**
     *  文件上传类型
     *
     * @param  string  $type
     * @return array
     */
    public function fileType($type)
    {
        $config = config('admin.file');
        $post_max_size = ini_get('post_max_size');

        switch($type){
            case FileTypeConst::IMAGE_EN :
                return [
                    'path' => $config[FileTypeConst::IMAGE_EN]['path'],
                    'ext' => $config[FileTypeConst::IMAGE_EN]['ext'],
                    'upload_max_filesize' => $post_max_size ,
                    'title'=>'Image files'];
            case FileTypeConst::FILE_EN :
                return [
                    'path' => $config[FileTypeConst::FILE_EN]['path'],
                    'ext' => $config[FileTypeConst::FILE_EN]['ext'],
                    'upload_max_filesize' => $post_max_size ,
                    'title'=>'File files'];
            case FileTypeConst::VIDEO_EN :
                return [
                    'path' => $config[FileTypeConst::VIDEO_EN]['path'],
                    'ext' => $config[FileTypeConst::VIDEO_EN]['ext'],
                    'upload_max_filesize' => $post_max_size,
                    'title'=>'File files'];
            case 'audio':
                return [
                    'path' => $config[FileTypeConst::AUDIO_EN]['path'],
                    'ext' => $config[FileTypeConst::AUDIO_EN]['ext'],
                    'upload_max_filesize' => $post_max_size,
                    'title'=>'File files'];
        }
    }
    /**
     *  上传处理返回参数
     *
     * @param  int      $code
     * @param  string   $msg
     * @param  string   $path
     * @param  string   $name
     * @return array
     */
    public function result($code, $msg, $path = '', $name = '')
    {
        return [
            'code'      => $code,
            'msg'       => $msg,
            'path'      => $path,
            'name'      => $name
        ];
    }


    /**
     *  生成缩略图
     *
     * @param  string   $filename
     * @return array
     */
    public function thumbImage($filename)
    {
        $thumbImage = str_replace("img", "thumb", $filename);

        //缩略图路径生成
        $thumbPath = explode(DS, $thumbImage);
        array_pop($thumbPath);
        $thumbPath = implode(DS, $thumbPath);
        $this->checkPath($thumbPath);

        //处理图片并保存
        $config = config('admin.thumb_image');
        $image = \think\Image::open($filename);
        $image->thumb($config['width'], $config['height'], \think\Image::THUMB_SCALING)->save($thumbImage);

    }
}