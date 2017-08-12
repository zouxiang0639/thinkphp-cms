<?php
namespace app\common\bls\fragment\model;



use app\common\base\BasicModel;

class FragmentModel extends BasicModel
{
    // 设置完整的数据表（包含前缀）
    protected $name         = 'fragment';
    public $primaryKey      = 'fragment_id';

}