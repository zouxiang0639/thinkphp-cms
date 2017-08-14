<?php
namespace app\common\consts\common;

/**
 * 表单类型
 */
class CommonFormInputConst
{

    const TEXT      = 1;
    const SELECT    = 2;
    const CHECKOUT  = 3;
    const RADIO     = 4;
    const PASSWORD  = 5;
    const TEXTAREA  = 6;

    const TEXT_DESC         = '文本框';
    const SELECT_DESC       = '下拉框';
    const CHECKOUT_DESC     = '复选框';
    const RADIO_DESC        = '单选框';
    const PASSWORD_DESC     = '密码框';
    const TEXTAREA_DESC     = '文本域';


    public static function desc()
    {
        return [
            self::TEXT      => self::TEXT_DESC,
            self::SELECT    => self::SELECT_DESC,
            self::CHECKOUT  => self::CHECKOUT_DESC,
            self::RADIO     => self::RADIO_DESC,
            self::PASSWORD  => self::PASSWORD_DESC,
            self::TEXTAREA  => self::TEXTAREA_DESC
        ];
    }

    public static function getDesc($item)
    {
        return array_get(self::desc(), $item);
    }
}