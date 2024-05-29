<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);

//echo "<pre>";print_r($arResult['VARIABLES']);echo "</pre>";die;


if(!empty($arResult['VARIABLES']['SECTION_CODE'])) {
	$iblock = \Bitrix\Iblock\IblockTable::getList([
		'select' => ['SECTION_PAGE_URL'],
		'filter' => ['ID' => $arParams['IBLOCK_ID']],
		'cache' => ['ttl' => 31536000]
	])->fetch();
	//echo "<pre>";print_r($iblock['SECTION_PAGE_URL']);echo "</pre>";die;
	if(strpos($iblock['SECTION_PAGE_URL'], '#SECTION_CODE_PATH#') !== false) {
		$sectionCode = $arResult['VARIABLES']['SECTION_CODE'];

		$filter = ['IBLOCK_ID' => $arParams['IBLOCK_ID'], 'CODE' => $sectionCode];


		$obCache = new CPHPCache();
		if($obCache->InitCache(36000, serialize($filter), '/iblock/catalog/redirect')) {
			$section = $obCache->GetVars();
		}
		elseif($obCache->StartDataCache()) {
			$section = [];

			$iterator = CIBLockSection::GetList([], $filter, false, ["IBLOCK_ID", "ID", "SECTION_PAGE_URL", "IBLOCK_TYPE_ID", "IBLOCK_SECTION_ID", "CODE", "SECTION_ID", "NAME"]);

			if(defined("BX_COMP_MANAGED_CACHE")) {
				global $CACHE_MANAGER;
				$CACHE_MANAGER->StartTagCache('/iblock/catalog/redirect');

				if($row = $iterator->GetNext()) {
					$section = $row;
					$CACHE_MANAGER->RegisterTag('iblock_id_'.$arParams['IBLOCK_ID']);
				}

				$CACHE_MANAGER->EndTagCache();
			}
			else {
				if($row = $iterator->GetNext()) {
					$section = $row;
				}
			}

			$obCache->EndDataCache($section);
		}

		if($section['SECTION_PAGE_URL']) {
			LocalRedirect($section['SECTION_PAGE_URL'], false, '301 Moved permanently');
		}
	}
}