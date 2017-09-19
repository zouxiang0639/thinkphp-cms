<?php
namespace app\common\library\format;

/**
 * 导航状态类型
 */
class FormatMoney
{
    /**
     * @param $yuan
     * @return int
     */
    public static function yuan2fen($yuan)
    {
        return intval(bcmul($yuan, 100));
    }

    /**
     * @param $fen
     * @return string
     */
    public static function fen2yuan($fen,$separator=',')
    {
        return number_format(bcdiv($fen, 100, 2), 2, '.', $separator);
    }

    public static function yuan($yuan,$separator=','){
        return number_format($yuan, 2, '.', $separator);
    }
}