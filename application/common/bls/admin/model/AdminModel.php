<?php
namespace app\common\bls\admin\model;

use app\common\base\BasicModel;

class AdminModel extends BasicModel
{
    public $name = 'admin';

    protected $type = [
        'role' => 'array'
    ];
}