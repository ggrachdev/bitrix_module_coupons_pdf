Модуль, который генерирует купоны на скидку при определенных суммах заказа и отправляет пользователю PDF флаер с купоном.
Для генерации PDF используется библиотека DOMPDF

Настройте под себя /ggrachdev.coupons_pdf/classes/general/CouponsPdf/Handler/OrderHandler.php либо не инициализируйте и создайте свое событие OnSaleStatusOrderChange по аналогии. Там нужно в фасад передать шаблон с плейсхолдером #COUPON# (Код купона) и #PERCENT# (Процент скидки)


```php
// init.php

\Bitrix\Main\Loader::includeModule('ggrachdev.coupons_pdf');

use \GGrach\CouponsPdf\Handler\OrderHandler;
use \GGrach\CouponsPdf\Facade\CouponsPdfFacade;

// Задаем правило, что при сумме заказа от 10000 до 19999 создавать купон в правило работы с корзиной
// с id = 2 и процент скидки для шаблона будет 5%
CouponsPdfFacade::addRuleGenerate(10000, 19999, 2, 5);

// Задаем правило, что при сумме заказа от 20000 до 29999 создавать купон в правило работы с корзиной
// с id = 3 и процент скидки для шаблона будет 10%
CouponsPdfFacade::addRuleGenerate(20000, 29999, 3, 10);
CouponsPdfFacade::addRuleGenerate(30000, 10000000, 4, 15);

// Можете кастомизировать событие в классе:
OrderHandler::initialize();

// ИЛИ:

// Если кастомизировать хотите через другую точку входа, то вызовите метод handle у CouponsPdfFacade:
$viewPdf = 'Ваш шаблон html из которого будет сформирован PDF с заменой #COUPON# и #PERCENT# на сгенерированные значения';

// Куда сохранять PDF
$pathFolderPdfGenerate = $_SERVER['DOCUMENT_ROOT'] . '/upload/coupons_pdf';

// Надо передать ID заказа, путь куда сохранять pdf и шаблон
CouponsPdfFacade::handle($order->getId(), $pathFolderPdfGenerate, $viewPdf);
```

Модуль писался как разовый под проект, кастомизируйте под себя, скопировать и чтобы заработало не выйдет, по возможности - все распишу подробнее и сделаю более универсальным
