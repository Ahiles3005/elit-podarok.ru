<?
use \Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

class sdvv_supermenu extends CModule {
    var $MODULE_ID = "sdvv.supermenu";
    var $MODULE_VERSION;
    var $MODULE_VERSION_DATE;
    var $MODULE_NAME;
    var $MODULE_DESCRIPTION;
    var $PARTNER_URI;
    var $PARTNER_NAME;

    function sdvv_supermenu() {
        $arModuleVersion = array();

        $path = str_replace("\\", "/", __file__);
        $path = substr($path, 0, strlen($path) - strlen("/index.php"));
        include ($path . "/version.php");

        if (is_array($arModuleVersion) && array_key_exists("VERSION", $arModuleVersion)) {
            $this->MODULE_VERSION = $arModuleVersion["VERSION"];
            $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
        }

        $this->MODULE_NAME = Loc::getMessage("SDVV_SUPERMENU_MODULE_NAME");
        $this->MODULE_DESCRIPTION = Loc::getMessage("SDVV_SUPERMENU_MODULE_DESCRIPTION");
        $this->PARTNER_NAME = Loc::getMessage('SDVV_SUPERMENU_PARTNER');
        $this->PARTNER_URI = Loc::getMessage('SDVV_SUPERMENU_PARTNER_URL');
    }

    function InstallDB() {
        RegisterModule($this->MODULE_ID);
        RegisterModuleDependences('main', 'OnPageStart', $this->MODULE_ID);

        return true;
    }

    function UnInstallDB() {
        UnRegisterModuleDependences('main', 'OnPageStart', $this->MODULE_ID);
        UnRegisterModule($this->MODULE_ID);

        return true;
    }

    function DoInstall() {
        if (!IsModuleInstalled($this->MODULE_ID))
        {
            $this->InstallDB();
            //$this->InstallEvents();
            //$this->InstallFiles();
        }
    }

    function DoUninstall() {
        $this->UnInstallDB();
        //$this->UnInstallEvents();
        //$this->UnInstallFiles();
    }
}