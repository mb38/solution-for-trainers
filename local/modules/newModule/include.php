<?php
use Bitrix\Main\Loader;

Loader::registerAutoLoadClasses(
    'newModule',
    array(
        'CIBlockPropertyCProp' => 'lib/CIBlockPropertyCProp.php',
        "CCustomTypeHtml" => "lib/customtypehtml.php",
    )
);