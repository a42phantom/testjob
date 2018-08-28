<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>
<?
global $USER;
CModule::IncludeModule('IBLOCK');
$bs = new CIBlockSection;
$el = new CIBlockElement;

//ID инфоблока валют
$IBLOCK_ID = 4;

//Получаем разделы валют
$arOrder = array("SORT"=>"ASC");
$arFilter = array("IBLOCK_ID" => $IBLOCK_ID);
$arSelect = array("NAME", "ID");
$rsSections = $bs::GetList($arOrder, $arFilter, false, $arSelect);

//Очищаем старые данные
while ($arSect = $rsSections->GetNext()) {
	$bs::Delete($arSect['ID']);
}

define('DB_URL','https://api.exchangeratesapi.io');

$arCurrencies = array();

//Запрос к базе валют
for ($startDay = 29; $startDay >= 0; $startDay--) {
	$date = new DateTime('-' . $startDay . ' days');
	$date = $date->format('Y-m-d');
	
	$url = DB_URL . '/' . $date;
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_URL, $url);
	$res = curl_exec($curl);
	//$test = file_get_contents(DB_URL . '/' . $date);
	curl_close($curl);
	
	$result = json_decode($res);
	
	foreach ($result->rates as $key => $currenci) {
		$arCurrencies[$key]['ITEMS'][$date] = $currenci;
	}
}

//Заполняем новые данные в инфоблок
foreach ($arCurrencies as $key => $arCurrenci) {
	$arFields = Array(
		"IBLOCK_ID" => $IBLOCK_ID,
		"NAME" => $key
	);
	$ID = $bs->Add($arFields);
	
	foreach ($arCurrenci['ITEMS'] as $key => $value) {
		$PROP = array();
		$PROP['VALUE'] = $value;
		$curDate = $DB->FormatDate($key, 'YYYY-MM-DD', CSite::GetDateFormat("SHORT"));
		$PROP['DATE'] = $curDate;
		
		$arLoadProductArray = Array(
			"MODIFIED_BY"    => $USER->GetID(),
			"IBLOCK_SECTION_ID" => $ID,
			"IBLOCK_ID"      => $IBLOCK_ID,
			"PROPERTY_VALUES"=> $PROP,
			"NAME"           => $curDate,
			"ACTIVE"         => "Y"
		);
		
		if($PRODUCT_ID = $el->Add($arLoadProductArray))
			echo "New ID: ".$PRODUCT_ID;
		else
			echo "Error: ".$el->LAST_ERROR;
	}
	
}

?>