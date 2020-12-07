<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Задание 8");
?>

<?$APPLICATION->IncludeComponent(
	"myComponents:yandexDisk",
	"",
	array(),
	false
);?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>