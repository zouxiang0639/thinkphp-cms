<?php
namespace app\common\bls\user\model;

use app\common\base\BasicModel;
use app\common\bls\user\traits\UserTrait;
use think\Collection;

class UserModel extends BasicModel
{
    use UserTrait;

    // 设置完整的数据表（包含前缀）
    protected $name = 'user';

    //格式化数据
    public function formatUsers()
    {
        if($this instanceof Collection) {
            $model = $this;
        } else {
            $model =  Collection::make([$this]);
        }
        $this->formatUser($model);
        return $model;
    }
}