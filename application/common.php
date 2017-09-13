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

if (! function_exists('object_get')) {
    /**
     * Get an item from an object using "dot" notation.
     *
     * @param  object  $object
     * @param  string  $key
     * @param  mixed   $default
     * @return mixed
     */
    function object_get($object, $key, $default = null)
    {
        if(is_object($object)){
            $key = implode('->',explode('.',$key));
            if(isset($object->$key)){
                return $object->$key;
            }
        }
        return $default;
    }
}

if (! function_exists('thumb_get')) {
    /**
     * 获取缩略图
     *
     * @param  string  $path
     * @return mixed
     */
    function thumb_get($path)
    {
        $thumbPath = str_replace("img", "thumb", $path);
        if(file_exists(ROOT_PATH.'public'.$thumbPath)){
            return $thumbPath;
        }else{
            return $path;
        }
    }
}

if (! function_exists('fragment')) {

    /**
     * 获取碎片信息
     * @param $id
     * @param $field
     * @return mixed
     */
    function fragment($id, $field)
    {
        $fragment = \app\common\bls\fragment\FragmentBls::getAllFragment();
        return isset($fragment[$id]) ? $fragment[$id][$field] : '';
    }
}

