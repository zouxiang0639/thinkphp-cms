<?php

namespace app\common\bls\user\traits;

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
            $item->nickname = empty($this->nickname) ? '匿名' : $this->nickname;
            $item->avatar = empty($this->avatar) ? '/static/user/image/avatar.jpg' : $this->avatar;
        });
    }
}
