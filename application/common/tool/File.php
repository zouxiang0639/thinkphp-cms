<?php
namespace app\common\tool;

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
     * 图片上传
     *
     * @param  object  $request
     * @param  string  $arr
     * @return array
     */
    public function uploadPicture($request, $name)
    {

        // 获取表单上传文件
        $file = $request->file($name);

        if (empty($file)) {
            return [
                'code'      => 0,
                'message'   => '请选择上传文件',
            ];
        }
        $type   = self::fileType('img');

        // 移动到框架应用根目录/public/uploads/ 目录下
        $info = $file->validate(['ext' => $type['ext']])->move(ROOT_PATH . 'public'.$type['path']);

        if ($info) {

            // 上传失败成功
            return [
                'hash'      => $info->hash(),
                'saveName'  => $type['path'].$info->saveName,
                'info'      => $info->info,
                'code'      => 1
            ];
        } else {
            // 上传失败获取错误信息
            return [
                'code'      => 0,
                'message'   => $file->getError(),
            ];
        }

    }


    /**
     *  文件上传类型
     *
     * @param  string  $name
     * @return array
     */
    public function fileType($name)
    {
        $path   = '/uploads'.DS;
        switch($name){
            case 'img':
                return ['path' => $path.'img/', 'ext' => 'jpg,png'];
        }
    }
}