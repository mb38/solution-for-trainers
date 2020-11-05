<?php
function Handler($arFields) {
    if($arFields["IBLOCK_ID"] != 4) {
        $res = CIBlock::GetByID($arFields["IBLOCK_ID"]);
        $nameIBlock = '';
        if($ar_res = $res->GetNext()) {
            $nameIBlock = $ar_res["NAME"]; //Получаем имя инфоблока
        }
        $el = new CIBlockElement;

        $arLoadProductArray = Array(
            "IBLOCK_ID"      => 4,                          //ID инфоблока для записи логов
            "TIMESTAMP_X"    => $arFields["TIMESTAMP_X"],   //дата создания/изменения элемента
            "NAME"           => $arFields["ID"],            //ID логируемого злемента
            "PREVIEW_TEXT"   => $nameIBlock.' -> '. $arFields["NAME"], //Имя инфоблока -> Имя элемента.
        );

        $el->Add($arLoadProductArray);
    }
}

AddEventHandler("iblock", "OnAfterIBlockElementUpdate", Array("CIBlockUpdateHandler", "OnAfterIBlockElementUpdateHandler"));

class CIBlockUpdateHandler {
    function OnAfterIBlockElementUpdateHandler(&$arFields) {
        Handler($arFields);
    }
}

AddEventHandler("iblock", "OnAfterIBlockElementAdd", Array("CIBlockAddHandler", "OnAfterIBlockElementAddHandler"));

class CIBlockAddHandler {
    function OnAfterIBlockElementAddHandler(&$arFields) {
        Handler($arFields);
    }
}