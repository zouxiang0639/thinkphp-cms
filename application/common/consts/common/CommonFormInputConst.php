<?php
namespace app\common\consts\common;

/**
 * 表单类型
 */
class CommonFormInputConst
{

    const TEXT      = 1;
    const SELECT    = 2;
    const CHECKBOX  = 3;
    const RADIOS    = 4;
    const PASSWORD  = 5;
    const TEXTAREA  = 6;
    const ONE_IMAGE = 7;
    const MULTI_IMAGE = 8;
    const ONE_FILE  = 9;
    const ONE_AUDIO  = 10;

    const TEXT_DESC         = '文本框';
    const SELECT_DESC       = '下拉框';
    const CHECKBOX_DESC     = '复选框';
    const RADIOS_DESC       = '单选框';
    const PASSWORD_DESC     = '密码框';
    const TEXTAREA_DESC     = '文本域';
    const ONE_IMAGE_DESC    = '单张图片上传';
    const MULTI_IMAGE_DESC  = '多图上传';
    const ONE_FILE_DESC     = '单个文件上传';
    const ONE_AUDIO_DESC    = '单个音频上传';


    public static function desc()
    {
        return [
            self::TEXT      => self::TEXT_DESC,
            self::SELECT    => self::SELECT_DESC,
            self::CHECKBOX  => self::CHECKBOX_DESC,
            self::RADIOS    => self::RADIOS_DESC,
            self::PASSWORD  => self::PASSWORD_DESC,
            self::TEXTAREA  => self::TEXTAREA_DESC,
            self::ONE_IMAGE => self::ONE_IMAGE_DESC,
            self::MULTI_IMAGE => self::MULTI_IMAGE_DESC,
            self::ONE_FILE  => self::ONE_FILE_DESC,
            self::ONE_AUDIO => self::ONE_AUDIO_DESC
        ];
    }

    public static function getDesc($item)
    {
        return array_get(self::desc(), $item);
    }
}