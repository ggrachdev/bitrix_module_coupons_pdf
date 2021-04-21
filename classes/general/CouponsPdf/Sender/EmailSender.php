<?php

namespace GGrach\CouponsPdf\Sender;

use \Bitrix\Main\Mail\Event;

final class EmailSender {

    public static function sendNotice(array $params = [], array $files = [], string $eventName = 'SEND_DELIVERY_COUPON') {

        $arData = [
            "EVENT_NAME" => $eventName,
            "LID" => 's1',
            $params,
        ];

        if (!empty($files)) {
            $arData['FILE'] = $files;
        }

        return Event::send($arData)->IsSuccess();
    }

}
