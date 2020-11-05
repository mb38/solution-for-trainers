<?php
function deleteLogAgent() {
    $result = CIBlockElement::getList(
        [],
        ['IBLOCK_ID' => 4],
        false,
        false,
        ['ID']
    );

    while($element = $result->Fetch()) {
        if(count($element['ID']) > 10) {
            CIBlockElement::Delete($element['ID']);
        }
    }

    return "deleteLogAgent();";
}