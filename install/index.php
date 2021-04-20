<?

global $MESS;
$strPath2Lang = str_replace("\\", "/", __FILE__);
$strPath2Lang = substr($strPath2Lang, 0, strlen($strPath2Lang) - 18);
@include(GetLangFileName($strPath2Lang . "/lang/", "/install/index.php"));
IncludeModuleLangFile($strPath2Lang . "/install/index.php");

class ggrachdev_coupons_pdf extends CModule {

    public $MODULE_ID = 'ggrachdev.coupons_pdf';
    public $MODULE_VERSION;
    public $MODULE_VERSION_DATE;
    public $MODULE_NAME;
    public $MODULE_DESCRIPTION;

    /**
     * Инициализация модуля для страницы "Управление модулями"
     */
    function __construct() {
        include($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/{$this->MODULE_ID}/install/version.php");
        $this->MODULE_NAME = GetMessage('GGRACHDEV_PDF_COUPON_MODULNAME');
        $this->MODULE_DESCRIPTION = GetMessage('GGRACHDEV_PDF_COUPON_DESC');
        $this->MODULE_VERSION = $arModuleVersion['VERSION'];
        $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        $this->PARTNER_NAME = GetMessage("GGRACHDEV_OPTIONS_PARTNER_NAME");
        $this->PARTNER_URI = GetMessage("GGRACHDEV_OPTIONS_PARTNER_URI");
    }

    /**
     * Устанавливаем модуль
     */
    public function DoInstall() {
        RegisterModule($this->MODULE_ID);
    }

    /**
     * Удаляем модуль
     */
    public function DoUninstall() {
        UnRegisterModule($this->MODULE_ID);
    }

    /**
     * Добавляем почтовые события
     *
     * @return bool
     */
    public function InstallEvents() {
        return true;
    }

    /**
     * Удаляем почтовые события
     *
     * @return bool
     */
    public function UnInstallEvents() {
        return true;
    }

    /**
     * Копируем файлы административной части
     *
     * @return bool
     */
    public function InstallFiles() {
        return true;
    }

    /**
     * Удаляем файлы административной части
     *
     * @return bool
     */
    public function UnInstallFiles() {
        return true;
    }

}

?>
