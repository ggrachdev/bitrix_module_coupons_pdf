<?php

namespace GGrach\CouponsPdf\Generator;

final class CouponGenerator {

    private int $userId;
    private int $idRuleBasket;

    public function __construct(int $userId, int $idRuleBasket) {
        $this->userId = $userId;
        $this->idRuleBasket = $idRuleBasket;
    }

    public function getIdRuleBasket(): int {
        return $this->idRuleBasket;
    }

    public function getUserId(): int {
        return $this->userId;
    }

    public function generate(string $couponCode): bool {

        $fields = [
            'DISCOUNT_ID' => $this->getIdRuleBasket(), // id правила корзины
            'ACTIVE_FROM' => null, // выставляем без ограничения к началу даты активности купона
            'ACTIVE_TO' => null, // выставляем без ограничения к окончанию даты активности купона                  
            'TYPE' => \Bitrix\Sale\Internals\DiscountCouponTable::TYPE_ONE_ORDER, // выставляем тип купона TYPE_ONE_ORDER - использовать на один заказ, TYPE_MULTI_ORDER - использовать на несколько заказов 
            'MAX_USE' => 1, // выставляем максимальное кол-во применений купона
            'COUPON' => $couponCode,
        ];

        $resultGenerate = \Bitrix\Sale\Internals\DiscountCouponTable::add($fields);

        return $resultGenerate->isSuccess();
    }

    public function generateForUser(string $couponCode): bool {

        $fields = [
            'DISCOUNT_ID' => $this->getIdRuleBasket(), // id правила корзины
            'ACTIVE_FROM' => null, // выставляем без ограничения к началу даты активности купона
            'ACTIVE_TO' => null, // выставляем без ограничения к окончанию даты активности купона                  
            'TYPE' => \Bitrix\Sale\Internals\DiscountCouponTable::TYPE_ONE_ORDER, // выставляем тип купона TYPE_ONE_ORDER - использовать на один заказ, TYPE_MULTI_ORDER - использовать на несколько заказов
            'MAX_USE' => 1, // выставляем максимальное кол-во применений купона
            'COUPON' => $couponCode,
            'USER_ID' => $this->getUserId()
        ];

        $resultGenerate = \Bitrix\Sale\Internals\DiscountCouponTable::add($fields);

        return $resultGenerate->isSuccess();
    }

}
