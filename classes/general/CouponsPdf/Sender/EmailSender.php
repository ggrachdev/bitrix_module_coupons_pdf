<?php

namespace GGrach\CouponsPdf\Sender;

use \Bitrix\Main\Mail\Event;

final class EmailSender {

    public static function sendNotice(array $params = [], array $files = [], string $eventName = 'SEND_DELIVERY_COUPON'): bool {

        $arData = [
            "EVENT_NAME" => $eventName,
            "LID" => SITE_ID,
            "C_FIELDS" => $params,
        ];
        
        if(!empty($files)) {
            $arData['FILE'] = $files;
        }
        
        $res = Event::send($arData);
        
        return $res->IsSuccess();
    }

}
