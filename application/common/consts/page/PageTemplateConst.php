<?php
namespace app\common\consts\page;

/**
 * 模版类型
 */
class PageTemplateConst
{
    //模型
    const PAGE  = [1, '单页模版', 'manage/page/edit'];
    const INFO  = [2, '信息模版', 'manage/info/index'];
    const GOODS = [3, '产品模版', 'manage/goods/index'];

    //页面
    const ARTICLE = [11, '关于我们', ' article '];
    const NEWS  = [12, '新闻', 'news'];

    //内页
    const NEWS_INFO  = [13, '新闻内页', 'news_info'];

    public static function desc()
    {
        return [
            self::PAGE[0]       => self::PAGE[1],
            self::INFO[0]       => self::INFO[1],
            self::GOODS[0]      => self::GOODS[1],
            self::ARTICLE[0]    => self::ARTICLE[1],
            self::NEWS[0]       => self::NEWS[1],
            self::NEWS_INFO[0]  => self::NEWS_INFO[1],
        ];
    }

    public static function groupDesc()
    {
        return [
            self::PAGE[0]       => self::PAGE[1],
            self::INFO[0]       => self::INFO[1],
            self::GOODS[0]      => self::GOODS[1],
        ];
    }

    public static function pageDesc()
    {

        return [
            self::ARTICLE[0]    => self::ARTICLE[1],
            self::NEWS[0]       => self::NEWS[1],
        ];
    }

    public static function infoDesc()
    {
        return [
            self::NEWS_INFO[0]       => self::NEWS_INFO[1],
        ];
    }


   /* public static function getJson()
    {
        $array = [
            ['name'=>static::PAGE[1], 'value'=>static::PAGE[0], 'city'=>[ //单页模型
                ['name'=>static::ABOUT[1],'value'=>static::ABOUT[0]]
            ]],
            ['name'=>static::INFO[1], 'value'=>static::INFO[0], 'city'=>[ //信息模型
                ['name'=>static::NEWS[1],'value'=>static::NEWS[0], 'area'=>[
                    ['name'=>static::NEWS_INFO[1], 'value'=>static::NEWS_INFO[0]]
                ]]
            ]]
        ];

        return json_encode($array, JSON_UNESCAPED_UNICODE);
    }*/

    public static function getDesc($item)
    {
        return array_get(self::desc(), $item, '');
    }

}