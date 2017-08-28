<?php

namespace app\common\bls\user\traits;

use app\common\consts\user\UserStatusConst;
use think\Collection;

trait UserTrait
{
    /**
     * 属性填充
     * @param Collection $items
     * @return Collection
     */
    public function formatUser(Collection $items)
    {
        return $items->each(function ($item) {
            $item->birthday = substr($item->birthday, 0, 10);
            $item->nickname = empty($item->nickname) ? '匿名' : $item->nickname;
            $item->avatar = empty($item->avatar) ? '/static/user/image/avatar.jpg' : $item->avatar;
            $item->statusName = UserStatusConst::getDesc($item->status);
        });
    }
}
