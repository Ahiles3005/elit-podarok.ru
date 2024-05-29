<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
	die();
}
$this->setFrameMode(true);

//echo "<pre>";print_r($arResult);echo "</pre>";die;

if(!empty($arResult['VARIABLES']['ELEMENT_CODE'])) {
	$iblock = \Bitrix\Iblock\IblockTable::getList([
		'select' => ['DETAIL_PAGE_URL'],
		'filter' => ['ID' => $arParams['IBLOCK_ID']],
		'cache'  => ['ttl' => 31536000],
	])->fetch();

	if (strpos($iblock['DETAIL_PAGE_URL'], '#ELEMENT_CODE#') !== false) {
		$elementCode = $arResult['VARIABLES']['ELEMENT_CODE'];
		$filter = ['IBLOCK_ID' => $arParams['IBLOCK_ID'], 'CODE' => $elementCode];

		$obCache = new CPHPCache();
		if ($obCache->InitCache(36000, serialize($filter), '/iblock/catalog/redirect')) {
			$element = $obCache->GetVars();
		} elseif ($obCache->StartDataCache()) {
			$element = [];

			$iterator = CIBLockElement::GetList([], $filter, false, false, ['IBLOCK_ID', 'ID', 'DETAIL_PAGE_URL', 'CODE']);

			if (defined("BX_COMP_MANAGED_CACHE")) {
				global $CACHE_MANAGER;
				$CACHE_MANAGER->StartTagCache('/iblock/catalog/redirect');

				if ($row = $iterator->GetNext()) {
					$element = $row;
					$CACHE_MANAGER->RegisterTag('iblock_id_' . $arParams['IBLOCK_ID']);
				}

				$CACHE_MANAGER->EndTagCache();
			} else {
				if ($row = $iterator->GetNext()) {
					$element = $row;
				}
			}


			$obCache->EndDataCache($element);
		}


		if ($element['DETAIL_PAGE_URL']) {
			LocalRedirect($element['DETAIL_PAGE_URL'], false, '301 Moved permanently');
		}
	}
}