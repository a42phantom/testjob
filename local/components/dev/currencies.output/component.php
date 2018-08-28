<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die(); ?>
<?

if ($this->StartResultCache()) {
	if (!isset($_GET['DATE_FROM'])) {
		$date_from = new DateTime('-30days');
		$date_from = $date_from->format('Y-m-d');
		$date_from = $DB->FormatDate($date_from, 'YYYY-MM-DD', CSite::GetDateFormat("SHORT"));
	} else {
		$date_from = $_GET['DATE_FROM'];
	}
	if (!isset($_GET['DATE_TO'])) {
		$date_to = new DateTime();
		$date_to = $date_to->format('Y-m-d');
		$date_to = $DB->FormatDate($date_to, 'YYYY-MM-DD', CSite::GetDateFormat("SHORT"));
	} else {
		$date_to = $_GET['DATE_TO'];
	}

//Получаем разделы валют
	$arOrder = array("SORT"=>"ASC");
	$arFilter = array("IBLOCK_ID" => $arParams["IBLOCK_ID"]);
	$arSelect = array("NAME", "ID");
	$rsSections = CIBlockSection::GetList($arOrder, $arFilter, false, $arSelect);
	$arSectionlist = array();
	
	while($arSection = $rsSections->GetNext())
	{
		$arSectionlist[] = $arSection['ID'];
		$arResult['SECTIONS'][$arSection['ID']] = $arSection;
	}
	
	
	$arOrder = array();
	$arSelect = Array("ID", "NAME", "DATE_ACTIVE_FROM", "PROPERTY_DATE", "PROPERTY_VALUE", "IBLOCK_SECTION_ID");
	$arFilter = Array("IBLOCK_ID" => $arParams["IBLOCK_ID"], "SECTION_ID" => $arSectionlist, "ACTIVE"=>"Y");

//$DateFrom = "05.08.2018";
//$DateTo = "10.08.2018";
	
	$arFilter[">=PROPERTY_DATE"] = ConvertDateTime($date_from, "YYYY-MM-DD")." 00:00:00";
	$arFilter["<=PROPERTY_DATE"] = ConvertDateTime($date_to, "YYYY-MM-DD")." 23:59:59";
	
	$res = CIBlockElement::GetList($arOrder, $arFilter, false, false, $arSelect);
	
	while($ob = $res->GetNextElement())
	{
		$arFields = $ob->GetFields();
		$arResult['SECTIONS'][$arFields['IBLOCK_SECTION_ID']]['ITEMS'][] = $arFields;
	}
	
	$this->IncludeComponentTemplate();
}

?>
