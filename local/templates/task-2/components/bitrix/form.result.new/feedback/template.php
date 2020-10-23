<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<? if ($arResult["isFormErrors"] == "Y"): ?><?= $arResult["FORM_ERRORS_TEXT"]; ?><?endif; ?>
<?= $arResult["FORM_NOTE"] ?>

<?if ($arResult["isFormNote"] != "Y")
{
?>
<?=str_replace('<form','<form class="contact-form__form"',$arResult["FORM_HEADER"])?>
    <div class="contact-form__form-inputs" >
        <?
        foreach ($arResult["QUESTIONS"] as $FIELD_SID => $arQuestion)
        {
            if ($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] == 'hidden')
            {
                echo $arQuestion["HTML_CODE"];
            }
            if ($arQuestion != end($arResult["QUESTIONS"])) {
            ?>
                <div class="input contact-form__input" >
                    <label class="input__label">
                        <div class="input__label-text"><?=$arQuestion["CAPTION"]?></div>
                        <?=$arQuestion["HTML_CODE"] = str_replace('class="','class="input__input"',$arQuestion["HTML_CODE"])?>
                        <div class="input__notification" ></div >
                    </label >
                </div >
            <?
            } continue;
        }
        ?>
    </div>
    <?
    foreach ($arResult["QUESTIONS"] as $FIELD_SID => $arQuestion)
    {
        if ($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] == 'hidden')
        {
        echo $arQuestion["HTML_CODE"];
        }
        if ($arQuestion == end($arResult["QUESTIONS"]))
        {
            ?>
            <div class="contact-form__form-message" >
                <div class="input" >
                    <label class="input__label">
                        <div class="input__label-text"><?=$arQuestion["CAPTION"]?></div>
                        <?=$arQuestion["HTML_CODE"] = str_replace('class="','class="input__input"',$arQuestion["HTML_CODE"])?>
                        <div class="input__notification" ></div >
                    </label >
                </div >
            </div >
            <?
        } continue;
    }
?>

    <div class="contact-form__bottom" >
        <div class="contact-form__bottom-policy" > Нажимая &laquo;Отправить&raquo;, Вы &nbsp;подтверждаете, что ознакомлены, полностью согласны и &nbsp;принимаете условия &laquo;Согласия на&nbsp;обработку персональных данных&raquo;.
        </div >
        <input class="form-button contact-form__bottom-button" <?=(intval($arResult["F_RIGHT"]) < 10 ? "disabled=\"disabled\"" : "");?> type="submit" name="web_form_submit" value="<?=htmlspecialcharsbx(strlen(trim($arResult["arForm"]["BUTTON"])) <= 0 ? GetMessage("FORM_ADD") : $arResult["arForm"]["BUTTON"]);?>" />
    </div >
    <?=$arResult["FORM_FOOTER"]?>
<?
}
?>