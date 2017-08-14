<?php
namespace app\common\bls\configure\model;

use app\common\base\BasicModel;

class ConfigureModel extends BasicModel
{
    // 设置完整的数据表（包含前缀）
    protected $name         = 'configure';
    public $primaryKey      = 'configure_id';

}