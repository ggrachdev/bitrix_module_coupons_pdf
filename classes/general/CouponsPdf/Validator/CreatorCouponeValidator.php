<?php

namespace GGrach\CouponsPdf\Validator;

class CreatorCouponeValidator {

    public static function needGenerateCoupon(int $orderId): bool {
        $needGenerate = false;

        if (\Bitrix\Main\Loader::includeModule('sale')) {

            $order = \Bitrix\Sale\Order::load($orderId);
            
            if($order && $order->getField('STATUS_ID') === 'F')
            {
                $needGenerate = true;
            }
        }

        return $needGenerate;
    }

}
