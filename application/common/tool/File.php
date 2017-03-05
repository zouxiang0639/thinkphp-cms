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

}