<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
$isCatalog = true;

//echo '<pre>'; print_r($_REQUEST); echo '</pre>';die;

if (!empty($_REQUEST['find_section'])) {
    $sectionID = 0;
    $arFilter = [
        'IBLOCK_ID' => 26,
        //'DEPTH_LEVEL' => 1,
        'CODE' => $_REQUEST['find_section']
    ];
    $arSelect = [
        'ID'
    ];
    $rsSect = CIBlockSection::GetList(['ID' => 'asc'], $arFilter, false, $arSelect);
    while ($arSect = $rsSect->GetNext())
    {
        $sectionID = $arSect['ID'];
    }
    if ($sectionID == 0) {
        $isCatalog = false;
    }
}
//echo '<pre>'; print_r($isCatalog); echo '</pre>';

if ($isCatalog) {
    include($_SERVER["DOCUMENT_ROOT"]."/catalog/index.php");
} else {
    include($_SERVER["DOCUMENT_ROOT"]."/catalog/seo_filter.php");
}