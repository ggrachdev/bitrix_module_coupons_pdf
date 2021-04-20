<?

include_once 'classes/general/Libs/dompdf/autoload.inc.php';

\Bitrix\Main\Loader::registerAutoLoadClasses('ggrachdev.coupons_pdf', [
    "\GGrach\CouponsPdf\Generator\CouponGenerator" => "classes/general/CouponsPdf/Generator/CouponGenerator.php",
    "\GGrach\CouponsPdf\Generator\PdfGenerator" => "classes/general/CouponsPdf/Generator/PdfGenerator.php",
    "\GGrach\CouponsPdf\Validator\CreatorCouponeValidator" => "classes/general/CouponsPdf/Validator/CreatorCouponeValidator.php",
    "\GGrach\CouponsPdf\Sender\EmailSender" => "classes/general/CouponsPdf/Sender/EmailSender.php",
    "\GGrach\CouponsPdf\Facade\CouponsPdfFacade" => "classes/general/CouponsPdf/Facade/CouponsPdfFacade.php"
]);
?>