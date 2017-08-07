<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件

if (! function_exists('multiFile')) {

    /**
     * 多文件上传
     *
     * @param  string  $names
     * @param  string  $value
     * @param  array   $options
     * @return string
     */
    function multiFile($names, $value = '', $options = [])
    {
        if(!is_array($value)){
            $value = json_decode($value, true);
        }
        $name = str_replace("[", "-", $names);
        $name = str_replace("]", "", $name);

        $html       = "<script type='text/html' id='{$name}-item-wrapper'>
                            <li id='{$name}{id}'>
                                <input id='{$name}-{id}' type='hidden' name='{$name}_1path[]' value='{filepath}'>
                                <input  type='text' class='form-control text' name='{$name}_1name[]' value=''  placeholder='标题'>
                                <input  type='text' class='form-control text' name='{$name}_1type[]' value='' placeholder='文件类型 如PDF'>

                                <a class='btn btn-primary' href=\"javascript:upload_one_image('文件上传','#{$name}-{id}');\">替换</a>
                            </li>
                      </script><ul id='{$name}' class='pic - list unstyled'>";

        if(is_array($value)){
            foreach ($value as $k => $v){
                $html  .= "<li id='{$name}{$k}'>
                                <input id='{$name}-{$k}' type='hidden' name='{$name}_1path[]' value='{$v['path']}'>
                                <input  type='text' class='form-control text' name='{$name}_1name[]' value='{$v['name']}' title='标题'>
                                <input  type='text' class='form-control text' name='{$name}_1type[]' value='{$v['type']}' title='文件类型 如PDF'>
                                <a class='btn btn-primary' href=\"javascript:upload_one_image('文件上传', '#{$name}-{$k}');\">替换</a>
                           </li>";
            }
        }
        $html      .="</ul>
                        <input type='hidden' class='' name='{$name}' value=''>
                        <a  href=\"javascript:upload_multi_image('文件上传','#{$name}','{$name}-item-wrapper','file');\"
                        class='btn btn-primary'>选择文件</a>";
        return $html;
    }
}

if (! function_exists('format_bytes')) {

    /**
     * 格式化字节大小
     * @param  number $size      字节数
     * @param  string $delimiter 数字和单位分隔符
     * @return string            格式化后的带单位的大小
     */
    function format_bytes($size, $delimiter = '')
    {
        $units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');
        for ($i = 0; $size >= 1024 && $i < 5; $i++) $size /= 1024;
        return round($size, 2) . $delimiter . $units[$i];
    }
}

