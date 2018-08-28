<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");?>
<?

$APPLICATION->SetTitle("валюты");

?>

<? $APPLICATION->IncludeComponent(
	"dev:currencies.output",
	"",
	Array(
		"IBLOCK_ID" => 4
	),
	false
);?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>