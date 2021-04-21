<?php

namespace GGrach\CouponsPdf\Handler;

use \GGrach\CouponsPdf\Facade\CouponsPdfFacade;
use \Bitrix\Main\EventManager;

final class OrderHandler {

    public static function initialize() {
        EventManager::getInstance()->addEventHandler(
            'sale',
            'OnSaleStatusOrderChange',
            [
                \GGrach\CouponsPdf\Handler\OrderHandler::class, 'OnSaleStatusOrderChange'
            ]
        );
    }

    public function OnSaleStatusOrderChange($event) {
        $parameters = $event->getParameters();
        if ($parameters['VALUE'] === 'F') {
            $order = $parameters['ENTITY'];

            $domain = (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . strtok($_SERVER['SERVER_NAME'], ':');
            $viewPdf = str_replace(
                [
                    '#IMAGES_PATH#',
                    '#BACKGROUND_IMAGE_NAME#',
                ],
                [
                    $domain . '/local/templates/aspro_optimus/templates_coupons/flyer/images/',
                    'flyer.jpg',
                ],
                \file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/local/templates/aspro_optimus/templates_coupons/flyer/Flyer.html')
            );

            $pathFolderPdfGenerate = $_SERVER['DOCUMENT_ROOT'] . '/upload/coupons_pdf';

            CouponsPdfFacade::handle($order->getId(), $pathFolderPdfGenerate, $viewPdf);
        }

        return new \Bitrix\Main\EventResult(
            \Bitrix\Main\EventResult::SUCCESS
        );
    }

}
