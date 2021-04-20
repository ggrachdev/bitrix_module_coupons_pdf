<?php

namespace GGrach\CouponsPdf\Validator;

final class CreatorCouponeValidator {

    public static function needGenerateCoupon(int $orderId, int $minSummForGenerate, int $maxSummForGenerate): bool {
        $needGenerate = false;

        if (\Bitrix\Main\Loader::includeModule('sale')) {

            $order = \Bitrix\Sale\Order::load($orderId);

            if (
                $order &&
                $order->getField('STATUS_ID') === 'F' &&
                $order->getBasket()->getPrice() >= $minSummForGenerate && 
                $order->getBasket()->getPrice() <= $maxSummForGenerate
            ) {
                $needGenerate = true;
            }
        }

        return $needGenerate;
    }

}
