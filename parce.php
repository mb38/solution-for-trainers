<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
if (!$USER->IsAdmin()) {
    LocalRedirect('/');
}
\Bitrix\Main\Loader::includeModule('iblock');

$row = 1;
$IBLOCK_ID = 3;

$el = new CIBlockElement;
$arProps = [];

$rsElement = CIBlockElement::getList(
    [],
    ['IBLOCK_ID' => $IBLOCK_ID],
    false,
    false,
    ['ID', 'NAME']
);

while ($ob = $rsElement->GetNextElement()) {
    $arFields = $ob->GetFields();
    $key = str_replace(['»', '«', '(', ')'], '', $arFields['NAME']);
    $key = strtolower($key);
    $arKey = explode(' ', $key);
    $key = '';
    foreach ($arKey as $part) {
        if (strlen($part) > 2) {
            $key .= trim($part) . ' ';
        }
    }
    $key = trim($key);
}

$rsProp = CIBlockPropertyEnum::GetList(
    ["SORT" => "ASC", "VALUE" => "ASC"],
    ['IBLOCK_ID' => $IBLOCK_ID]
);
while ($arProp = $rsProp->Fetch()) {
    $key = trim($arProp['VALUE']);
    $arProps[$arProp['PROPERTY_CODE']][$key] = $arProp['ID'];
}

$rsElements = CIBlockElement::GetList(
    [],
    ['IBLOCK_ID' => $IBLOCK_ID],
    false,
    false,
    ['ID']
);
while ($element = $rsElements->GetNext()) {
    CIBlockElement::Delete($element['ID']);
}

if (($handle = fopen($_SERVER['DOCUMENT_ROOT']."/local/dev-import/parce.csv", "r")) !== false) {
    while (($data = fgetcsv($handle, 1000, ",")) !== false) {
        if ($row == 1) {
            $row++;
            continue;
        }
        $row++;

        $PROP['SALARY_VALUE'] = $data[0];
        $PROP['OFFICE'] = $data[2];
        $PROP['EMAIL'] = $data[3];

        $PROP['DATE'] = date('d.m.Y');
        $PROP['SALARY_TYPE'] = $arProps['SALARY_TYPE']['='];
        $PROP['TYPE'] = $arProps['TYPE']['Рабочие'];
        $PROP['ACTIVITY'] = $arProps['ACTIVITY']['Стажировка'];
        $PROP['SCHEDULE'] = $arProps['SCHEDULE']['Полный день'];
        $PROP['FIELD'] = $arProps['FIELD']['Персонал'];
        $PROP['OFFICE'] = $arProps['OFFICE']['Свеза Ресурс'];
        $PROP['LOCATION'] = $arProps['LOCATION']['Санкт-Петербург'];

        foreach ($PROP as $key => &$value) {
            $value = trim($value);
            $value = str_replace('\n', '', $value);
            if (stripos($value, '•') !== false) {
                $value = explode('•', $value);
                array_splice($value, 0, 1);
                foreach ($value as &$str) {
                    $str = trim($str);
                }
            }
        }

        $arLoadProductArray = [
            "MODIFIED_BY" => $USER->GetID(),
            "IBLOCK_SECTION_ID" => false,
            "IBLOCK_ID" => $IBLOCK_ID,
            "PROPERTY_VALUES" => $PROP,
            "NAME" => $data[2],
            "ACTIVE" => end($data) ? 'Y' : 'N',
        ];

        if ($PRODUCT_ID = $el->Add($arLoadProductArray)) {
            echo "Добавлен элемент с ID : " . $PRODUCT_ID . "<br>";
        } else {
            echo "Error: " . $el->LAST_ERROR . '<br>';
        }
    }
    fclose($handle);
}


