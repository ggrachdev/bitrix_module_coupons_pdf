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

    public static function generatePdfFileName(int $userId, int $orderId, string $email): string {
        return \md5($userId . '_' . $orderId . '_' . $email);
    }

    public static function handle(int $idOrder, string $pathFolderPdfGenerate, string $viewPdf): bool {

        $wasSuccessGenerateCoupon = false;

        if (!empty(self::getRulesGenerateCoupons()) && \Bitrix\Main\Loader::includeModule('sale')) {

            $order = \Bitrix\Sale\Order::load($idOrder);

            if ($order) {
                $userId = $order->getUserId();
                $propertyCollection = $order->getPropertyCollection();

                // email куда отправляем купон
                $emailPropValue = $propertyCollection->getUserEmail()->getValue();

                foreach (self::getRulesGenerateCoupons() as $rule) {
                    $fileNamePdf = self::generatePdfFileName($userId, $idOrder, $emailPropValue);
                    $pdfGenerator = new PdfGenerator($pathFolderPdfGenerate, $fileNamePdf);

                    if (
                        !\is_file($pdfGenerator->getPathFilePdf()) &&
                        CreatorCouponeValidator::needGenerateCoupon($idOrder, $rule['minSumm'], $rule['maxSumm'])
                    ) {
                        $couponCode = \CatalogGenerateCoupon();
                        $couponGenerator = new CouponGenerator($userId, $rule['idRuleBasket']);

                        if ($couponGenerator->generate($couponCode)) {

                            $view = str_replace(
                                [
                                    '#COUPON#',
                                    '#PERCENT#'
                                ],
                                [
                                    $couponCode,
                                    'Скидку '.$rule['percent'] . '%'
                                ],
                                $viewPdf
                            );

                            try {
                                $filePathPdfCoupon = $pdfGenerator->generate($view);

                                $successSend = EmailSender::sendNotice(
                                        [
                                            'EMAIL' => $emailPropValue,
                                            'COUPON' => $couponCode,
                                            'PERCENT' => $rule['percent']
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
