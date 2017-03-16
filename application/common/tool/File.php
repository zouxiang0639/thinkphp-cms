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
     * @param  object  $request
     * @param  string  $arr
     * @param  string  $type
     * @return array
     */
    public function fileUpload($request, $name, $type)
    {

        // 获取表单上传文件
        $file = $request->file($name);

        //没有文件返回错误
        if (empty($file)) {
            return [
                'code'      => 0,
                'msg'       => '请选择上传文件',
            ];
        }

        $fileType   = self::fileType($type);

        //根据文件hash值 去查询是否有这个文件
        $hash   = $file->hash();
        $info = FileModel::where(['hash'=>$hash])->column(['0,path,name']);
        if($info){
            // 上传失败成功
            return [
                'code'      => 1,
                'msg'       => '上传成功',
                'path'      => $info[0]['path'],
                'name'      => $info[0]['name'],
            ];
        }else{

            $info = $file->validate(['ext' => $fileType['ext']])->move(ROOT_PATH . 'public'.$fileType['path']);
            $base    = request()->root();
            $root    = strpos($base, '.') ? DS.ltrim(dirname($base), DS) : $base;
            $path    = $root.$fileType['path'].$info->saveName;


            //文件写入数据库利于管理防止重复上传
            FileModel::create([
                'path'      => $path,
                'name'      => $info->info['name'],
                'type'      => $type,
                'hash'      => $hash,
            ]);

            if ($info) {

                // 上传成功
                return [
                    'code'      => 1,
                    'path'      => $path,
                    'name'      => $info->info['name'],
                    'msg'       => '上传成功'
                ];
            } else {

                // 上传失败获取错误信息
                return [
                    'code'      => 0,
                    'msg'       => $file->getError(),
                ];
            }
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
                return ['path' => $path.'file/', 'ext' => 'txt,pdf,doc,docx,xls,xlsx,ppt,pptx,zip,rar', 'upload_max_filesize' => '10240' ,'title'=>'File files'];
            case 'video':
                return ['path' => $path.'video/', 'ext' => 'mp4,avi,wmv,rm,rmvb,mkv', 'upload_max_filesize' => '10240'];
            case 'audio':
                return ['path' => $path.'audio/', 'ext' => 'mp4,avi,wmv,rm,rmvb,mkv', 'upload_max_filesize' => '10240'];
        }
    }
}