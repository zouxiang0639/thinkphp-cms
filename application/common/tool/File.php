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

        $type   = self::fileType($type);
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

            $info = $file->validate(['ext' => $type['ext']])->move(ROOT_PATH . 'public'.$type['path']);
            $base    = request()->root();
            $root    = strpos($base, '.') ? ltrim(dirname($base), DS) : $base;
            $path    = '/'.$root.$type['path'].$info->saveName;
            //图片写入数据库利于管理
            FileModel::create([
                'path'      => $path,
                'name'      => $info->info['name'],
                'type'      => 'image',
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
        $path   = '/uploads'.DS;
        switch($type){
            case 'image':
                return ['path' => $path.'img/', 'ext' => 'jpg,png'];
        }
    }
}