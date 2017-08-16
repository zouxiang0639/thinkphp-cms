<?php

namespace app\common\bls\admin\traits;

use think\Collection;

trait AdminTrait
{
    /**
     * 属性填充
     * @param Collection $items
     * @return Collection
     */
    protected function formatAdmin(Collection $items)
    {
        return $items->each(function ($item) {
            $item->lastLoginIpName = long2ip($item->last_login_ip);
        });
    }
}
