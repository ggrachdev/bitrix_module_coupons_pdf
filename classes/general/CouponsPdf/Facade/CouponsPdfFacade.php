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

    public static function handle(int $idOrder, string $pathFolderPdfGenerate, string $viewPdf): bool {

        $wasSuccessGenerateCoupon = false;

        if (!empty(self::getRulesGenerateCoupons()) && \Bitrix\Main\Loader::includeModule('sale')) {

            $order = \Bitrix\Sale\Order::load($idOrder);

            if ($order) {
                $userId = $order->getUserId();
                $propertyCollection = $order->getPropertyCollection();

                // email куда отправляем купон
                $emailPropValue = $propertyCollection->getUserEmail();

                foreach (self::getRulesGenerateCoupons() as $rule) {
                    if (CreatorCouponeValidator::needGenerateCoupon($idOrder, $rule['minSumm'], $rule['maxSumm'])) {

                        $couponCode = \CatalogGenerateCoupon();
                        $couponGenerator = new CouponGenerator($userId, $rule['idRuleBasket']);

                        if ($couponGenerator->generate($couponCode)) {

                            $pdfGenerator = new PdfGenerator($pathFolderPdfGenerate, \str_shuffle(\uniqid() . \uniqid()));

                            $view = str_replace(
                                [
                                    '#COUPON#',
                                    '#PERCENT#'
                                ],
                                [
                                    $couponCode,
                                    $rule['percent'] . '%'
                                ],
                                $view
                            );

                            try {
                                $filePathPdfCoupon = $pdfGenerator->generate($view);

                                $successSend = EmailSender::sendNotice(
                                        [
                                            'EMAIL' => $emailPropValue
                                        ],
                                        [
                                            $filePathPdfCoupon
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
