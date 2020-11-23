<?php

use \Bitrix\Main\Localization\Loc,
    \Bitrix\Main\EventManager;

Loc::loadMessages(__FILE__);

class newModule extends CModule
{
    var $MODULE_ID  = 'newModule';

    function __construct()
    {
        $arModuleVersion = array();
        include __DIR__ . '/version.php';

        $this->MODULE_ID = 'newModule';
        $this->MODULE_VERSION = $arModuleVersion['VERSION'];
        $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        $this->MODULE_NAME = Loc::getMessage('IEX_CPROP_MODULE_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage('IEX_CPROP_MODULE_DESC');

        $this->FILE_PREFIX = 'cprop';
        $this->MODULE_FOLDER = str_replace('.', '_', $this->MODULE_ID);
        $this->FOLDER = 'bitrix';

        $this->INSTALL_PATH_FROM = '/' . $this->FOLDER . '/modules/' . $this->MODULE_ID;
    }

    function isVersionD7()
    {
        return true;
    }

    function DoInstall()
    {
        global $DOCUMENT_ROOT, $APPLICATION;
        if($this->isVersionD7())
        {
            $this->InstallDB();
            $this->InstallEvents();
            $this->InstallFiles();

            RegisterModule($this->MODULE_ID);
            $APPLICATION->IncludeAdminFile(
                "Установка newModule",
                $DOCUMENT_ROOT."/local/modules/newModule/install/step.php"
            );
        }
        else
        {
            $APPLICATION->ThrowException(Loc::getMessage('IEX_CPROP_INSTALL_ERROR_VERSION'));
        }
    }

    function DoUninstall()
    {
        global $DOCUMENT_ROOT, $APPLICATION;

        UnRegisterModule($this->MODULE_ID);

        $this->UnInstallFiles();
        $this->UnInstallDB();

        $APPLICATION->IncludeAdminFile(
            "Удаление newModule",
            $DOCUMENT_ROOT."/local/modules/newModule/install/unstep.php"
        );
        $this->UnInstallEvents();
    }


    function InstallDB()
    {
        RegisterModuleDependences("main", "OnUserTypeBuildList", "newModule", "CCustomTypeHtml", "GetUserTypeDescription");

        return true;
    }

    function UnInstallDB()
    {
        UnRegisterModuleDependences("main", "OnUserTypeBuildList", "newModule", "CCustomTypeHtml", "GetUserTypeDescription");

        return true;
    }

    function installFiles()
    {
        return true;
    }

    function uninstallFiles()
    {
        return true;
    }

    function getEvents()
    {
        return [
            [
                'FROM_MODULE' => 'iblock',
                'EVENT' => 'OnIBlockPropertyBuildList',
                'TO_METHOD' => 'GetUserTypeDescription'
            ],
        ];
    }

    function InstallEvents()
    {
//        EventManager::getInstance()->registerEventHandler(
//            "main",
//            "CIBlockPropertyCProp",
//            $this->MODULE_ID,
//            "lib\CIBlockPropertyCProp",
//            "appendScriptsToPage"
//        );

        $classHandler = 'CIBlockPropertyCProp';
        $eventManager = EventManager::getInstance();

        $arEvents = $this->getEvents();
        foreach($arEvents as $arEvent){
            $eventManager->registerEventHandler(
                $arEvent['FROM_MODULE'],
                $arEvent['EVENT'],
                $this->MODULE_ID,
                $classHandler,
                $arEvent['TO_METHOD']
            );
        }

        return true;
    }

    function UnInstallEvents()
    {
        $classHandler = 'CIBlockPropertyCProp';
        $eventManager = EventManager::getInstance();

        $arEvents = $this->getEvents();
        foreach($arEvents as $arEvent){
            $eventManager->unregisterEventHandler(
                $arEvent['FROM_MODULE'],
                $arEvent['EVENT'],
                $this->MODULE_ID,
                $classHandler,
                $arEvent['TO_METHOD']
            );
        }

        return true;
    }
}