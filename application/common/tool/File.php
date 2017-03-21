<?php
namespace app\common\tool;

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
            $replaceFile = FileModel::get($id);
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
            $info = FileModel::where(['hash'=>$hash])->column(['0,path,name']);
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
            FileModel::create([
                'path'  => $path,
                'name'  => $info->info['name'],
                'group' => $type,
                'hash'  => $hash,
            ]);
        }else if ($filePath && is_object($replaceFile)){

            //更新替换上传的文件数据
            $replaceFile->save([
                'path'  => $path,
                'name'  => $info->info['name'],
                'group' => $type,
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
        $path   = DS.'uploads'.DS;
        switch($type){
            case 'image':
                return ['path' => $path.'img/', 'ext' => 'jpg,jpeg,png,gif,bmp4', 'upload_max_filesize' => '10240' ,'title'=>'Image files'];
            case 'file':
                return ['path' => $path.'file/', 'ext' => 'txt,dwg,pdf,doc,docx,xls,xlsx,ppt,pptx,zip,rar', 'upload_max_filesize' => '10240' ,'title'=>'File files'];
            case 'video':
                return ['path' => $path.'video/', 'ext' => 'mp4,avi,wmv,rm,rmvb,mkv', 'upload_max_filesize' => '10240'];
            case 'audio':
                return ['path' => $path.'audio/', 'ext' => 'mp3,wma,wav', 'upload_max_filesize' => '10240'];
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
        $image = \think\Image::open($filename);
        $image->thumb(300, 300, \think\Image::THUMB_SCALING)->save($thumbImage);

    }
}