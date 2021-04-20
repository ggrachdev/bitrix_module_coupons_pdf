<?php

namespace GGrach\CouponsPdf\Facade;

use \GGrach\CouponsPdf\Validator\CreatorCouponeValidator;
use \GGrach\CouponsPdf\Generator\CouponGenerator;
use \GGrach\CouponsPdf\Generator\PdfGenerator;
use \GGrach\CouponsPdf\Sender\EmailSender;

final class CouponsPdfFacade {

    protected static array $rulesGenerateCoupons = [];

    public static function addRuleGenerate(int $minSumm, int $maxSumm, int $idRuleBasket, int $percentDiscount): void {
        self::$rulesGenerateCoupons[] = [
            'minSumm' => $minSumm,
            'maxSumm' => $maxSumm,
            'idRuleBasket' => $idRuleBasket,
            'percent' => $percentDiscount
        ];
    }

    public static function handle(int $idOrder): bool {
        
        $wasSuccessGenerateCoupon = false;
        
        if (!empty(self::getRulesGenerateCoupons()) && \Bitrix\Main\Loader::includeModule('sale')) {

            $order = \Bitrix\Sale\Order::load($idOrder);

            if ($order) {
                $userId = $order->getUserId();

                foreach (self::getRulesGenerateCoupons() as $rule) {
                    if (CreatorCouponeValidator::needGenerateCoupon($idOrder, $rule['minSumm'], $rule['maxSumm'])) {
                        
                        $couponCode = \CatalogGenerateCoupon();
                        $couponGenerator = new CouponGenerator($userId, $rule['idRuleBasket']);

                        if ($couponGenerator->generate($couponCode)) {

                            $pdfGenerator = new PdfGenerator($_SERVER['DOCUMENT_ROOT'] . '/ajax/coupons/files/', \uniqid());

                            $view = \file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/ajax/coupons/templates/flyer/Flyer.html');

                            $view = str_replace(
                                [
                                    '#COUPON#',
                                    '#IMAGES_PATH#',
                                    '#BACKGROUND_IMAGE_NAME#',
                                    '#PERCENT#'
                                ],
                                [
                                    $couponCode,
                                    'https://site.ru/ajax/coupons/templates/flyer/images/',
                                    'flyer.jpg',
                                    $rule['percent'].'%'
                                ],
                                $view
                            );

                            try {
                                $filePdfCoupon = $pdfGenerator->generate($view);

                                $successSend = EmailSender::sendNotice(
                                        [
                                            'EMAIL' => 'ggrach@email.ru'
                                        ],
                                        [
                                            $filePdfCoupon
                                        ]
                                );
                                
                                $wasSuccessGenerateCoupon = true;
                            } catch (Exception $exc) {
                                
                            }
                        }
                    }
                }
            }
        }
        
        return $wasSuccessGenerateCoupon;
    }

    public static function getRulesGenerateCoupons(): array {
        return self::$rulesGenerateCoupons;
    }

}
