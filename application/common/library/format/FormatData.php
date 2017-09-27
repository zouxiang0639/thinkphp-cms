<?php
namespace app\common\library\format;

/**
 * 格式化数据
 */
class FormatData
{
    /**
     * 多图上传 Excel导出格式化
     * @param $data
     * @return string
     */
    public static function photosFormat($data)
    {
        $data = json_decode($data);
        $array = '';
        foreach ($data as $value) {
            $value = array_values((array)$value);
            $array[] = implode(':', $value);
        }
        return implode('|'.PHP_EOL, $array);
    }
    /**
     * 多图上传 Excel导入格式化
     * @param $data
     * @return string
     */
    public static function photosFormatUn($data)
    {
        $data = explode('|', $data);
        $array = [];
        foreach ((array)$data as $value) {
            $value = explode(':', trim($value));
            $array[] = [
                'path' => isset($value[0]) ? $value[0] : '',
                'name' => isset($value[1]) ? $value[1] : '',
            ];
        }

        return json_encode($array);
    }
}