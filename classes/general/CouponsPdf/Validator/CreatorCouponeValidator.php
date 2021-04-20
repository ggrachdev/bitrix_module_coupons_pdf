<?php

namespace GGrach\CouponsPdf\Validator;

class CreatorCouponeValidator {

    public static function needGenerateCoupon(int $orderId, int $minSummForGenerate): bool {
        $needGenerate = false;

        if (\Bitrix\Main\Loader::includeModule('sale')) {

            $order = \Bitrix\Sale\Order::load($orderId);

            if (
                $order &&
                $order->getField('STATUS_ID') === 'F' &&
                $order->getBasket()->getPrice() >= $minSummForGenerate
            ) {
                $needGenerate = true;
            }
        }

        return $needGenerate;
    }

}
