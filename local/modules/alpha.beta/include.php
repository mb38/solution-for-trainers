<?php

global $DB;
$db_type = strtolower($DB->type);

\Bitrix\Main\Loader::registerAutoLoadClasses('alpha.beta', [
    'CIBlockPropertyCProp' => 'lib/CIBlockPropertyCProp.php',
    'CCustomTypeHtml' => 'lib/customtypehtml.php'
]);