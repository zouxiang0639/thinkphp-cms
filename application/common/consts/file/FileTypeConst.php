<?php
namespace app\common\consts\file;

/**
 * 文件类型类型
 */
class FileTypeConst
{
    const IMAGE = 1;
    const FILE = 2;
    const VIDEO = 3;
    const AUDIO = 4;

    const IMAGE_DESC = '图片';
    const FILE_DESC = '文件';
    const VIDEO_DESC = '视屏';
    const AUDIO_DESC = '音频';

    const IMAGE_EN = 'image';
    const FILE_EN = 'file';
    const VIDEO_EN = 'video';
    const AUDIO_EN = 'audio';


    public static function desc()
    {
        return [
            self::IMAGE => self::IMAGE_DESC,
            self::FILE => self::FILE_DESC,
            self::VIDEO => self::VIDEO_DESC,
            self::AUDIO => self::AUDIO_DESC,
        ];
    }

    public static function en()
    {
        return [
            self::IMAGE => self::IMAGE_EN,
            self::FILE => self::FILE_EN,
            self::VIDEO => self::VIDEO_EN,
            self::AUDIO => self::AUDIO_EN,
        ];
    }
    public static function number()
    {
        return [
            self::IMAGE_EN => self::IMAGE,
            self::FILE_EN => self::FILE,
            self::VIDEO_EN => self::VIDEO,
            self::AUDIO_EN => self::AUDIO,
        ];
    }

    public static function getDesc($item)
    {
        return array_get(self::desc(), $item);
    }

    public static function getNumber($item)
    {
        return array_get(self::number(), $item);
    }

    public static function getEn($item)
    {
        return array_get(self::en(), $item);
    }
}