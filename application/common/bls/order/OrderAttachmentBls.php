<?php
namespace app\common\bls\order;

use app\common\bls\order\model\OrderAttachmentModel;

class OrderAttachmentBls
{

    public static function createAllOrderAttachment($data)
    {
        $model = new OrderAttachmentModel();
        return $model->saveAll($data);
    }
}