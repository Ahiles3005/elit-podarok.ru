<?
use Bitrix\Main\Loader,
	Bitrix\Main\ModuleManager;
?>
<?CNext::AddMeta(
	array(
		'og:description' => $arElement['PREVIEW_TEXT'],
		'og:image' => (($arElement['PREVIEW_PICTURE'] || $arElement['DETAIL_PICTURE']) ? CFile::GetPath(($arElement['PREVIEW_PICTURE'] ? $arElement['PREVIEW_PICTURE'] : $arElement['DETAIL_PICTURE'])) : false),
	)
);?>
<?$sViewElementTemplate = ($arParams["ELEMENT_TYPE_VIEW"] == "FROM_MODULE" ? $arTheme["CATALOG_PAGE_DETAIL"]["VALUE"] : $arParams["ELEMENT_TYPE_VIEW"]);?>
<?$hide_left_block = ($arTheme["LEFT_BLOCK_CATALOG_DETAIL"]["VALUE"] == "Y" ? "N" : "Y");
$arWidePage = array("element_3", "element_4", "element_5");

//set offer view type
$typeTmpDetail = 0;
if($arSection['UF_ELEMENT_DETAIL'])
	$typeTmpDetail = $arSection['UF_ELEMENT_DETAIL'];
else
{
	if($arSection["DEPTH_LEVEL"] > 2)
	{
		$sectionParent = CNextCache::CIBlockSection_GetList(array('CACHE' => array("MULTI" =>"N", "TAG" => CNextCache::GetIBlockCacheTag($arParams["IBLOCK_ID"]))), array('GLOBAL_ACTIVE' => 'Y', "ID" => $arSection["IBLOCK_SECTION_ID"], "IBLOCK_ID" => $arParams["IBLOCK_ID"]), false, array("ID", "IBLOCK_ID", "NAME", "UF_ELEMENT_DETAIL"));
		if($sectionParent['UF_ELEMENT_DETAIL'] && !$typeTmpDetail)
			$typeTmpDetail = $sectionParent['UF_ELEMENT_DETAIL'];

		if(!$typeTmpDetail)
		{
			$sectionRoot = CNextCache::CIBlockSection_GetList(array('CACHE' => array("MULTI" =>"N", "TAG" => CNextCache::GetIBlockCacheTag($arParams["IBLOCK_ID"]))), array('GLOBAL_ACTIVE' => 'Y', "<=LEFT_BORDER" => $arSection["LEFT_MARGIN"], ">=RIGHT_BORDER" => $arSection["RIGHT_MARGIN"], "DEPTH_LEVEL" => 1, "IBLOCK_ID" => $arParams["IBLOCK_ID"]), false, array("ID", "IBLOCK_ID", "NAME", "UF_ELEMENT_DETAIL"));
			if($sectionRoot['UF_ELEMENT_DETAIL'] && !$typeTmpDetail)
				$typeTmpDetail = $sectionRoot['UF_ELEMENT_DETAIL'];
		}
	}
	else
	{
		$sectionRoot = CNextCache::CIBlockSection_GetList(array('CACHE' => array("MULTI" =>"N", "TAG" => CNextCache::GetIBlockCacheTag($arParams["IBLOCK_ID"]))), array('GLOBAL_ACTIVE' => 'Y', "<=LEFT_BORDER" => $arSection["LEFT_MARGIN"], ">=RIGHT_BORDER" => $arSection["RIGHT_MARGIN"], "DEPTH_LEVEL" => 1, "IBLOCK_ID" => $arParams["IBLOCK_ID"]), false, array("ID", "IBLOCK_ID", "NAME", "UF_ELEMENT_DETAIL"));
		if($sectionRoot['UF_ELEMENT_DETAIL'] && !$typeTmpDetail)
			$typeTmpDetail = $sectionRoot['UF_ELEMENT_DETAIL'];
	}
}
if($typeTmpDetail)
{
	$rsTypes = CUserFieldEnum::GetList(array(), array("ID" => $typeTmpDetail));
	if($arType = $rsTypes->GetNext())
		$typeDetail = $arType['XML_ID'];
	if($typeDetail)
		$sViewElementTemplate = $typeDetail;
}

if(in_array($sViewElementTemplate, $arWidePage))
	$hide_left_block = "Y";
?>
<?$APPLICATION->SetPageProperty("HIDE_LEFT_BLOCK", $hide_left_block)?>
<?if($arParams["USE_SHARE"] == "Y" && $arElement && !in_array($sViewElementTemplate, $arWidePage)):?>
	<?$this->SetViewTarget('product_share');?>
	<div class="line_block share top <?=($arParams['USE_RSS'] == 'Y' ? 'rss-block' : '');?>">
		<?$APPLICATION->IncludeFile(SITE_DIR."include/share_buttons.php", Array(), Array("MODE" => "html", "NAME" => GetMessage('CT_BCE_CATALOG_SOC_BUTTON')));?>
	</div>
	<?$this->EndViewTarget();?>
<?endif;?>
<?$isWideBlock = (isset($arParams["DIR_PARAMS"]["HIDE_LEFT_BLOCK"]) ? $arParams["DIR_PARAMS"]["HIDE_LEFT_BLOCK"] : "");?>
<?if($arParams['AJAX_MODE'] == 'Y' && strpos($_SERVER['REQUEST_URI'], 'bxajaxid') !== false):?>
	<script type="text/javascript">
		setStatusButton();
	</script>
<?endif;?>
<?$sViewBigDataTemplate = ($arParams["BIGDATA_NORMAL"] ? $arParams["BIGDATA_NORMAL"] : "bigdata_1" );?>
<?$sViewBigDataExtTemplate = ($arParams["BIGDATA_EXT"] ? $arParams["BIGDATA_EXT"] : "bigdata_2" );?>
<div class="catalog_detail detail<?=($isWideBlock == "Y" ? " fixed_wrapper" : "");?> <?=$sViewElementTemplate;?>" itemscope itemtype="http://schema.org/Product">
    <?@include_once('page_blocks/'.$sViewElementTemplate.'.php');?>
</div>

<?CNext::checkBreadcrumbsChain($arParams, $arSection, $arElement);
//if($_SERVER['REMOTE_ADDR']=='95.105.86.47') {\bitrix\main\diag\debug::dumpToFile($APPLICATION->GetNavChain());}?>

<div class="clearfix"></div>
    <h3 class="title_block sm">Похожие товары</h3>
<?php
global $arrFilter;
$arrFilter = array("!=ID" => $arElement['ID']);

?>
<?$APPLICATION->IncludeComponent(
	"bitrix:catalog.section", 
	"catalog_block", 
	array(

			'USE_REGION' => 'N',
			'STORES' =>[],
			'SHOW_UNABLE_SKU_PROPS' => 'Y',
			'ALT_TITLE_GET' => 'SEO',
			'SEF_URL_TEMPLATES' =>
				array (
					'sections' => '',
					'section' => '#SECTION_CODE#/',
					'element' => '#ELEMENT_CODE#.html',
					'compare' => 'compare.php?action=#ACTION_CODE#',
					'smart_filter' => '#SECTION_CODE_PATH#/filter/#SMART_FILTER_PATH#/apply/',
					'page' => 'page-#PAGE_ID#/',
					'pagesect' => '#SECTION_CODE#/page-#PAGE_ID#/',
					'pagefilter' => '#SECTION_CODE#/filter/#SMART_FILTER_PATH#/apply/page-#PAGE_ID#/',
				),
			'IBLOCK_TYPE' => 'aspro_next_catalog',
			'IBLOCK_ID' => '26',
			'SHOW_COUNTER_LIST' => 'Y',
			'SECTION_ID' => $arSection['ID'],
			'SECTION_CODE' => $arSection['CODE'],
			'AJAX_REQUEST' => 'N',
			'ELEMENT_SORT_FIELD' => 'RAND',
			'ELEMENT_SORT_ORDER' => 'asc',
			'SHOW_DISCOUNT_TIME_EACH_SKU' => 'N',
			'ELEMENT_SORT_FIELD2' => 'CATALOG_AVAILABLE',
			'ELEMENT_SORT_ORDER2' => 'asc',
			'FILTER_NAME' => 'arrFilter',
			'INCLUDE_SUBSECTIONS' => 'Y',
			'PAGE_ELEMENT_COUNT' => '4',
			'LINE_ELEMENT_COUNT' => '4',
			'DISPLAY_TYPE' => 'block',
			'TYPE_SKU' => 'TYPE_1',
			'PROPERTY_CODE' =>
				array (
					0 => 'CML2_ARTICLE',
					1 => 'BRAND',
					2 => 'PROP_2033',
					3 => 'COLOR_REF2',
					4 => 'PROP_159',
					5 => 'PROP_2052',
					6 => 'PROP_2027',
					7 => 'PROP_2053',
					8 => 'PROP_2083',
					9 => 'PROP_2049',
					10 => 'PROP_2026',
					11 => 'PROP_2044',
					12 => 'PROP_162',
					13 => 'PROP_2065',
					14 => 'PROP_2054',
					15 => 'PROP_2017',
					16 => 'PROP_2055',
					17 => 'PROP_2069',
					18 => 'PROP_2062',
					19 => 'PROP_2061',
					20 => 'CML2_LINK',
					21 => '',
				),
			'SHOW_ARTICLE_SKU' => 'Y',
			'SHOW_MEASURE_WITH_RATIO' => 'N',
			'OFFERS_FIELD_CODE' =>
				array (
					0 => 'NAME',
					1 => 'CML2_LINK',
					2 => 'DETAIL_PAGE_URL',
					3 => '',
				),
			'OFFERS_PROPERTY_CODE' =>
				array (
					0 => '',
					1 => 'ARTICLE',
					2 => 'VOLUME',
					3 => 'SIZES',
					4 => 'COLOR_REF',
					5 => '',
				),
			'OFFERS_SORT_FIELD' => 'shows',
			'OFFERS_SORT_ORDER' => 'asc',
			'OFFERS_SORT_FIELD2' => 'shows',
			'OFFERS_SORT_ORDER2' => 'asc',
			'OFFER_TREE_PROPS' =>
				array (
				),
			'OFFERS_LIMIT' => '10',
			'SECTION_URL' => '/catalog/#SECTION_CODE_PATH#/',
			'DETAIL_URL' => '/catalog/#SECTION_CODE_PATH#/#ELEMENT_CODE#.html',
			'BASKET_URL' => '/basket/',
			'ACTION_VARIABLE' => 'action',
			'PRODUCT_ID_VARIABLE' => 'id',
			'PRODUCT_QUANTITY_VARIABLE' => 'quantity',
			'PRODUCT_PROPS_VARIABLE' => 'prop',
			'SECTION_ID_VARIABLE' => 'SECTION_ID',
			'SET_LAST_MODIFIED' => 'N',
			'AJAX_MODE' => 'N',
			'AJAX_OPTION_JUMP' => 'N',
			'AJAX_OPTION_STYLE' => 'Y',
			'AJAX_OPTION_HISTORY' => 'Y',
			'CACHE_TYPE' => 'A',
			'CACHE_TIME' => '3600000',
			'CACHE_GROUPS' => 'N',
			'CACHE_FILTER' => 'Y',
			'META_KEYWORDS' => '-',
			'META_DESCRIPTION' => '-',
			'BROWSER_TITLE' => '-',
			'ADD_SECTIONS_CHAIN' => 'N',
			'HIDE_NOT_AVAILABLE' => 'N',
			'HIDE_NOT_AVAILABLE_OFFERS' => 'L',
			'DISPLAY_COMPARE' => 'N',
			'SET_TITLE' => 'N',
			'SET_STATUS_404' => 'N',
			'SHOW_404' => 'N',
			'MESSAGE_404' => '',
			'FILE_404' => '',
			'PRICE_CODE' =>
				array (
					0 => 'Обмен с сайтом',
				),
			'USE_PRICE_COUNT' => 'Y',
			'SHOW_PRICE_COUNT' => '1',
			'PRICE_VAT_INCLUDE' => 'Y',
			'USE_PRODUCT_QUANTITY' => 'Y',
			'OFFERS_CART_PROPERTIES' =>[],
			'DISPLAY_TOP_PAGER' => 'N',
			'DISPLAY_BOTTOM_PAGER' => 'N',
			'PAGER_TITLE' => 'Товары',
			'PAGER_SHOW_ALWAYS' => 'N',
			'PAGER_TEMPLATE' => 'main_custom',
			'PAGER_DESC_NUMBERING' => 'N',
			'PAGER_DESC_NUMBERING_CACHE_TIME' => '36000',
			'PAGER_SHOW_ALL' => 'N',
			'AJAX_OPTION_ADDITIONAL' => '',
			'ADD_CHAIN_ITEM' => 'N',
			'SHOW_QUANTITY' => 'Y',
			'SHOW_QUANTITY_COUNT' => 'Y',
			'SHOW_DISCOUNT_PERCENT_NUMBER' => 'Y',
			'SHOW_DISCOUNT_PERCENT' => 'Y',
			'SHOW_DISCOUNT_TIME' => 'Y',
			'SHOW_OLD_PRICE' => 'Y',
			'CONVERT_CURRENCY' => 'Y',
			'CURRENCY_ID' => 'RUB',
			'USE_STORE' => 'N',
			'MAX_AMOUNT' => '20',
			'MIN_AMOUNT' => '10',
			'USE_MIN_AMOUNT' => 'N',
			'USE_ONLY_MAX_AMOUNT' => 'Y',
			'DISPLAY_WISH_BUTTONS' => 'Y',
			'LIST_DISPLAY_POPUP_IMAGE' => 'Y',
			'DEFAULT_COUNT' => '1',
			'SHOW_MEASURE' => 'Y',
			'SHOW_HINTS' => 'Y',
			'OFFER_HIDE_NAME_PROPS' => 'N',
			'SHOW_SECTIONS_LIST_PREVIEW' => NULL,
			'SECTIONS_LIST_PREVIEW_PROPERTY' => 'DESCRIPTION',
			'SHOW_SECTION_LIST_PICTURES' => 'Y',
			'USE_MAIN_ELEMENT_SECTION' => 'Y',
			'ADD_PROPERTIES_TO_BASKET' => 'Y',
			'PARTIAL_PRODUCT_PROPERTIES' => 'Y',
			'PRODUCT_PROPERTIES' =>[],
			'SALE_STIKER' => 'SALE_TEXT',
			'STIKERS_PROP' => 'HIT',
			'SHOW_RATING' => 'Y',
			'ADD_PICT_PROP' => 'MORE_PHOTO',
	),
	false
);?>

<?$arAllValues=$arSimilar=$arAccessories=array();
$arShowTabs = array("element_1", "element_2");
if(!in_array($sViewElementTemplate, $arWidePage)):
/*similar goods*/
$arExpValues=CNextCache::CIBlockElement_GetProperty($arParams["IBLOCK_ID"], $ElementID, array("CACHE" => array("TAG" =>CNextCache::GetIBlockCacheTag($arParams["IBLOCK_ID"]))), array("CODE" => "EXPANDABLES"));
if($arExpValues){
	$arAllValues["EXPANDABLES"]=$arExpValues;
}
/*accessories goods*/
$arAccessories=CNextCache::CIBlockElement_GetProperty($arParams["IBLOCK_ID"], $ElementID, array("CACHE" => array("TAG" =>CNextCache::GetIBlockCacheTag($arParams["IBLOCK_ID"]))), array("CODE" => "ASSOCIATED"));
if($arAccessories){
	$arAllValues["ASSOCIATED"]=$arAccessories;
}
?>

<?if($arAccessories || $arExpValues || (ModuleManager::isModuleInstalled("sale") && (!isset($arParams['USE_BIG_DATA']) || $arParams['USE_BIG_DATA'] != 'N'))){?>
	<?$bViewBlock = ($arParams["VIEW_BLOCK_TYPE"] == "Y");?>
	<?
	$arTab=array();
	if($arExpValues){
		$arTab["EXPANDABLES"]=($arParams["DETAIL_EXPANDABLES_TITLE"] ? $arParams["DETAIL_EXPANDABLES_TITLE"] : GetMessage("EXPANDABLES_TITLE"));
	}
	if($arAccessories){
		$arTab["ASSOCIATED"]=( $arParams["DETAIL_ASSOCIATED_TITLE"] ? $arParams["DETAIL_ASSOCIATED_TITLE"] : GetMessage("ASSOCIATED_TITLE"));
	}
	/* Start Big Data */
	if(ModuleManager::isModuleInstalled("sale") && (!isset($arParams['USE_BIG_DATA']) || $arParams['USE_BIG_DATA'] != 'N'))
		$arTab["RECOMENDATION"]= ( $arParams["TITLE_SLIDER"] ? $arParams["TITLE_SLIDER"] : GetMessage("RECOMENDATION_TITLE"));
	?>
	<?if($isWideBlock == "Y"):?>
		<div class="row">
			<div class="col-md-9">
	<?endif;?>

	<?if($bViewBlock):?>
		<div class="bottom_slider specials tab_slider_wrapp block_v">
			<?if($arTab):?>
				<?foreach($arTab as $code=>$title):?>
					<div class="wraps">
						<hr>
						<h4><?=$title;?></h4>
						<ul class="slider_navigation top custom_flex border">
							<li class="tabs_slider_navigation <?=$code?>_nav cur" data-code="<?=$code?>"></li>
						</ul>
						<ul class="tabs_content">
							<li class="tab <?=$code?>_wrapp cur" data-code="<?=$code?>">
								<?if($code=="RECOMENDATION"){?>
									<?
									$GLOBALS["CATALOG_CURRENT_ELEMENT_ID"] = $ElementID;
									?>
									<?include_once('page_blocks/'.$sViewBigDataTemplate.'.php');?>
									
								<?}else{?>
									<div class="flexslider loading_state shadow border custom_flex top_right" data-plugin-options='{"animation": "slide", "animationSpeed": 600, "directionNav": true, "controlNav" :false, "animationLoop": true, "slideshow": false, "controlsContainer": ".tabs_slider_navigation.<?=$code?>_nav", "counts": [4,3,3,2,1]}'>
										<ul class="tabs_slider <?=$code?>_slides slides">
											<?$GLOBALS['arrFilter'.$code] = array( "ID" => $arAllValues[$code] );?>
											<?$APPLICATION->IncludeComponent(
												"bitrix:catalog.top",
												"main",
												array(
													"USE_REGION" => ($arRegion ? "Y" : "N"),
													"STORES" => $arParams['STORES'],
													"TITLE_BLOCK" => $arParams["SECTION_TOP_BLOCK_TITLE"],
													"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
													"IBLOCK_ID" => $arParams["IBLOCK_ID"],
													"SALE_STIKER" => $arParams["SALE_STIKER"],
													"STIKERS_PROP" => $arParams["STIKERS_PROP"],
													"SHOW_RATING" => $arParams["SHOW_RATING"],
													"FILTER_NAME" => 'arrFilter'.$code,
													"ELEMENT_SORT_FIELD" => $arParams["ELEMENT_SORT_FIELD"],
													"ELEMENT_SORT_ORDER" => $arParams["ELEMENT_SORT_ORDER"],
													"ELEMENT_SORT_FIELD2" => $arParams["ELEMENT_SORT_FIELD2"],
													"ELEMENT_SORT_ORDER2" => $arParams["ELEMENT_SORT_ORDER2"],
													"SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
													"DETAIL_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["element"],
													"BASKET_URL" => $arParams["BASKET_URL"],
													"ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
													"PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
													"SECTION_ID_VARIABLE" => $arParams["SECTION_ID_VARIABLE"],
													"PRODUCT_QUANTITY_VARIABLE" => $arParams["PRODUCT_QUANTITY_VARIABLE"],
													"PRODUCT_PROPS_VARIABLE" => $arParams["PRODUCT_PROPS_VARIABLE"],
													"DISPLAY_COMPARE" => $arParams["USE_COMPARE"],
													"DISPLAY_WISH_BUTTONS" => $arParams["DISPLAY_WISH_BUTTONS"],
													"ELEMENT_COUNT" => $disply_elements,
													"SHOW_MEASURE_WITH_RATIO" => $arParams["SHOW_MEASURE_WITH_RATIO"],
													"SHOW_MEASURE" => $arParams["SHOW_MEASURE"],
													"LINE_ELEMENT_COUNT" => $arParams["TOP_LINE_ELEMENT_COUNT"],
													"PROPERTY_CODE" => $arParams["DETAIL_PROPERTY_CODE"],
													"PRICE_CODE" => $arParams['PRICE_CODE'],
													"USE_PRICE_COUNT" => $arParams["USE_PRICE_COUNT"],
													"SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],
													"PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
													"PRICE_VAT_SHOW_VALUE" => $arParams["PRICE_VAT_SHOW_VALUE"],
													"USE_PRODUCT_QUANTITY" => $arParams['USE_PRODUCT_QUANTITY'],
													"ADD_PROPERTIES_TO_BASKET" => (isset($arParams["ADD_PROPERTIES_TO_BASKET"]) ? $arParams["ADD_PROPERTIES_TO_BASKET"] : ''),
													"PARTIAL_PRODUCT_PROPERTIES" => (isset($arParams["PARTIAL_PRODUCT_PROPERTIES"]) ? $arParams["PARTIAL_PRODUCT_PROPERTIES"] : ''),
													"PRODUCT_PROPERTIES" => $arParams["PRODUCT_PROPERTIES"],
													"CACHE_TYPE" => $arParams["CACHE_TYPE"],
													"CACHE_TIME" => $arParams["CACHE_TIME"],
													"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
													"CACHE_FILTER" => $arParams["CACHE_FILTER"],
													"OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"],
													"OFFERS_FIELD_CODE" => $arParams["LIST_OFFERS_FIELD_CODE"],
													"OFFERS_PROPERTY_CODE" => $arParams["OFFERS_PROPERTY_CODE"],
													"OFFERS_SORT_FIELD" => $arParams["OFFERS_SORT_FIELD"],
													"OFFERS_SORT_ORDER" => $arParams["OFFERS_SORT_ORDER"],
													"OFFERS_SORT_FIELD2" => $arParams["OFFERS_SORT_FIELD2"],
													"OFFERS_SORT_ORDER2" => $arParams["OFFERS_SORT_ORDER2"],
													"OFFERS_LIMIT" => $arParams["LIST_OFFERS_LIMIT"],
													'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
													'CURRENCY_ID' => $arParams['CURRENCY_ID'],
													'HIDE_NOT_AVAILABLE' => $arParams['HIDE_NOT_AVAILABLE'],
													'HIDE_NOT_AVAILABLE_OFFERS' => $arParams["HIDE_NOT_AVAILABLE_OFFERS"],
													'VIEW_MODE' => (isset($arParams['TOP_VIEW_MODE']) ? $arParams['TOP_VIEW_MODE'] : ''),
													'ROTATE_TIMER' => (isset($arParams['TOP_ROTATE_TIMER']) ? $arParams['TOP_ROTATE_TIMER'] : ''),
													'TEMPLATE_THEME' => (isset($arParams['TEMPLATE_THEME']) ? $arParams['TEMPLATE_THEME'] : ''),
													'LABEL_PROP' => $arParams['LABEL_PROP'],
													'ADD_PICT_PROP' => $arParams['ADD_PICT_PROP'],
													'PRODUCT_DISPLAY_MODE' => $arParams['PRODUCT_DISPLAY_MODE'],

													'OFFER_ADD_PICT_PROP' => $arParams['OFFER_ADD_PICT_PROP'],
													'OFFER_TREE_PROPS' => $arParams['OFFER_TREE_PROPS'],
													'PRODUCT_SUBSCRIPTION' => $arParams['PRODUCT_SUBSCRIPTION'],
													'SHOW_DISCOUNT_PERCENT' => $arParams['SHOW_DISCOUNT_PERCENT'],
													'SHOW_DISCOUNT_PERCENT_NUMBER' => $arParams['SHOW_DISCOUNT_PERCENT_NUMBER'],
													'SHOW_OLD_PRICE' => $arParams['SHOW_OLD_PRICE'],
													'MESS_BTN_BUY' => $arParams['MESS_BTN_BUY'],
													'MESS_BTN_ADD_TO_BASKET' => $arParams['MESS_BTN_ADD_TO_BASKET'],
													'MESS_BTN_SUBSCRIBE' => $arParams['MESS_BTN_SUBSCRIBE'],
													'MESS_BTN_DETAIL' => $arParams['MESS_BTN_DETAIL'],
													'MESS_NOT_AVAILABLE' => $arParams['MESS_NOT_AVAILABLE'],
													'ADD_TO_BASKET_ACTION' => $basketAction,
													'SHOW_CLOSE_POPUP' => isset($arParams['COMMON_SHOW_CLOSE_POPUP']) ? $arParams['COMMON_SHOW_CLOSE_POPUP'] : '',
													'COMPARE_PATH' => $arResult['FOLDER'].$arResult['URL_TEMPLATES']['compare'],
												),
												false, array("HIDE_ICONS"=>"Y")
											);?>
										</ul>
									</div>
								<?}?>
							</li>
						</ul>
					</div>
				<?endforeach;?>
			<?endif;?>
		</div>
	<?else:?>
	<div class="bottom_slider specials tab_slider_wrapp">
		<div class="top_blocks">
			<ul class="tabs">
				<?$i=1;
				foreach($arTab as $code=>$title):?>
					<li data-code="<?=$code?>" <?=($code=="RECOMENDATION" ? "style='display:none;'" : "" );?> <?=($i==1 ? "class='cur'" : "")?>><span><?=$title;?></span></li>
					<?$i++;?>
				<?endforeach;?>
				<li class="stretch"></li>
			</ul>
			<ul class="slider_navigation top custom_flex border">
				<?$i=1;
				foreach($arTab as $code=>$title):?>
					<li class="tabs_slider_navigation <?=$code?>_nav <?=($i==1 ? "cur" : "")?>" data-code="<?=$code?>"></li>
					<?$i++;?>
				<?endforeach;?>
			</ul>
		</div>
	
		<?$disply_elements=($arParams["DISPLAY_ELEMENT_SLIDER"] ? $arParams["DISPLAY_ELEMENT_SLIDER"] : 10);?>
		<ul class="tabs_content">
			<?foreach($arTab as $code=>$title){?>
				<li class="tab <?=$code?>_wrapp" data-code="<?=$code?>">
					<?if($code=="RECOMENDATION"){?>
						<?
						$GLOBALS["CATALOG_CURRENT_ELEMENT_ID"] = $ElementID;
						?>
						<?include_once('page_blocks/'.$sViewBigDataTemplate.'.php');?>
					<?}else{?>
						<div class="flexslider loading_state shadow border custom_flex top_right" data-plugin-options='{"animation": "slide", "animationSpeed": 600, "directionNav": true, "controlNav" :false, "animationLoop": true, "slideshow": false, "controlsContainer": ".tabs_slider_navigation.<?=$code?>_nav", "counts": [4,3,3,2,1]}'>
						<ul class="tabs_slider <?=$code?>_slides slides">
							<?$GLOBALS['arrFilter'.$code] = array( "ID" => $arAllValues[$code] );?>
							<?$APPLICATION->IncludeComponent(
								"bitrix:catalog.top",
								"main",
								array(
									"USE_REGION" => ($arRegion ? "Y" : "N"),
									"STORES" => $arParams['STORES'],
									"TITLE_BLOCK" => $arParams["SECTION_TOP_BLOCK_TITLE"],
									"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
									"IBLOCK_ID" => $arParams["IBLOCK_ID"],
									"SALE_STIKER" => $arParams["SALE_STIKER"],
									"STIKERS_PROP" => $arParams["STIKERS_PROP"],
									"SHOW_RATING" => $arParams["SHOW_RATING"],
									"FILTER_NAME" => 'arrFilter'.$code,
									"ELEMENT_SORT_FIELD" => $arParams["ELEMENT_SORT_FIELD"],
									"ELEMENT_SORT_ORDER" => $arParams["ELEMENT_SORT_ORDER"],
									"ELEMENT_SORT_FIELD2" => $arParams["ELEMENT_SORT_FIELD2"],
									"ELEMENT_SORT_ORDER2" => $arParams["ELEMENT_SORT_ORDER2"],
									"SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
									"DETAIL_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["element"],
									"BASKET_URL" => $arParams["BASKET_URL"],
									"ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
									"PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
									"SECTION_ID_VARIABLE" => $arParams["SECTION_ID_VARIABLE"],
									"PRODUCT_QUANTITY_VARIABLE" => $arParams["PRODUCT_QUANTITY_VARIABLE"],
									"PRODUCT_PROPS_VARIABLE" => $arParams["PRODUCT_PROPS_VARIABLE"],
									"DISPLAY_COMPARE" => $arParams["USE_COMPARE"],
									"DISPLAY_WISH_BUTTONS" => $arParams["DISPLAY_WISH_BUTTONS"],
									"ELEMENT_COUNT" => $disply_elements,
									"SHOW_MEASURE_WITH_RATIO" => $arParams["SHOW_MEASURE_WITH_RATIO"],
									"SHOW_MEASURE" => $arParams["SHOW_MEASURE"],
									"LINE_ELEMENT_COUNT" => $arParams["TOP_LINE_ELEMENT_COUNT"],
									"PROPERTY_CODE" => $arParams["DETAIL_PROPERTY_CODE"],
									"PRICE_CODE" => $arParams['PRICE_CODE'],
									"USE_PRICE_COUNT" => $arParams["USE_PRICE_COUNT"],
									"SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],
									"PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
									"PRICE_VAT_SHOW_VALUE" => $arParams["PRICE_VAT_SHOW_VALUE"],
									"USE_PRODUCT_QUANTITY" => $arParams['USE_PRODUCT_QUANTITY'],
									"ADD_PROPERTIES_TO_BASKET" => (isset($arParams["ADD_PROPERTIES_TO_BASKET"]) ? $arParams["ADD_PROPERTIES_TO_BASKET"] : ''),
									"PARTIAL_PRODUCT_PROPERTIES" => (isset($arParams["PARTIAL_PRODUCT_PROPERTIES"]) ? $arParams["PARTIAL_PRODUCT_PROPERTIES"] : ''),
									"PRODUCT_PROPERTIES" => $arParams["PRODUCT_PROPERTIES"],
									"CACHE_TYPE" => $arParams["CACHE_TYPE"],
									"CACHE_TIME" => $arParams["CACHE_TIME"],
									"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
									"CACHE_FILTER" => $arParams["CACHE_FILTER"],
									"OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"],
									"OFFERS_FIELD_CODE" => $arParams["LIST_OFFERS_FIELD_CODE"],
									"OFFERS_PROPERTY_CODE" => $arParams["OFFERS_PROPERTY_CODE"],
									"OFFERS_SORT_FIELD" => $arParams["OFFERS_SORT_FIELD"],
									"OFFERS_SORT_ORDER" => $arParams["OFFERS_SORT_ORDER"],
									"OFFERS_SORT_FIELD2" => $arParams["OFFERS_SORT_FIELD2"],
									"OFFERS_SORT_ORDER2" => $arParams["OFFERS_SORT_ORDER2"],
									"OFFERS_LIMIT" => $arParams["LIST_OFFERS_LIMIT"],
									'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
									'CURRENCY_ID' => $arParams['CURRENCY_ID'],
									'HIDE_NOT_AVAILABLE' => $arParams['HIDE_NOT_AVAILABLE'],
									'HIDE_NOT_AVAILABLE_OFFERS' => $arParams["HIDE_NOT_AVAILABLE_OFFERS"],
									'VIEW_MODE' => (isset($arParams['TOP_VIEW_MODE']) ? $arParams['TOP_VIEW_MODE'] : ''),
									'ROTATE_TIMER' => (isset($arParams['TOP_ROTATE_TIMER']) ? $arParams['TOP_ROTATE_TIMER'] : ''),
									'TEMPLATE_THEME' => (isset($arParams['TEMPLATE_THEME']) ? $arParams['TEMPLATE_THEME'] : ''),
									'LABEL_PROP' => $arParams['LABEL_PROP'],
									'ADD_PICT_PROP' => $arParams['ADD_PICT_PROP'],
									'PRODUCT_DISPLAY_MODE' => $arParams['PRODUCT_DISPLAY_MODE'],

									'OFFER_ADD_PICT_PROP' => $arParams['OFFER_ADD_PICT_PROP'],
									'OFFER_TREE_PROPS' => $arParams['OFFER_TREE_PROPS'],
									'PRODUCT_SUBSCRIPTION' => $arParams['PRODUCT_SUBSCRIPTION'],
									'SHOW_DISCOUNT_PERCENT' => $arParams['SHOW_DISCOUNT_PERCENT'],
									'SHOW_DISCOUNT_PERCENT_NUMBER' => $arParams['SHOW_DISCOUNT_PERCENT_NUMBER'],
									'SHOW_OLD_PRICE' => $arParams['SHOW_OLD_PRICE'],
									'MESS_BTN_BUY' => $arParams['MESS_BTN_BUY'],
									'MESS_BTN_ADD_TO_BASKET' => $arParams['MESS_BTN_ADD_TO_BASKET'],
									'MESS_BTN_SUBSCRIBE' => $arParams['MESS_BTN_SUBSCRIBE'],
									'MESS_BTN_DETAIL' => $arParams['MESS_BTN_DETAIL'],
									'MESS_NOT_AVAILABLE' => $arParams['MESS_NOT_AVAILABLE'],
									'ADD_TO_BASKET_ACTION' => $basketAction,
									'SHOW_CLOSE_POPUP' => isset($arParams['COMMON_SHOW_CLOSE_POPUP']) ? $arParams['COMMON_SHOW_CLOSE_POPUP'] : '',
									'COMPARE_PATH' => $arResult['FOLDER'].$arResult['URL_TEMPLATES']['compare'],
								),
								false, array("HIDE_ICONS"=>"Y")
							);?>
						</ul>
						</div>
					<?}?>
				</li>
			<?}?>
		</ul>
	</div>
	<?endif;?>
	<?if($isWideBlock == "Y"):?>
			</div>
		</div>
	<?endif;?>
<?}?>
<?/*fix title after ajax form start*/
endif;
$arAdditionalData = $arNavParams = array();

$postfix = '';
global $arSite;
if(\Bitrix\Main\Config\Option::get("aspro.next", "HIDE_SITE_NAME_TITLE", "N")=="N")
	$postfix = ' - '.$arSite['SITE_NAME'];

$arAdditionalData['TITLE'] = htmlspecialcharsback($APPLICATION->GetTitle());
$arAdditionalData['WINDOW_TITLE'] = htmlspecialcharsback($APPLICATION->GetTitle('title').$postfix);

// dirty hack: try to get breadcrumb call params
for ($i = 0, $cnt = count($APPLICATION->buffer_content_type); $i < $cnt; $i++){
	if ($APPLICATION->buffer_content_type[$i]['F'][1] == 'GetNavChain'){
		$arNavParams = $APPLICATION->buffer_content_type[$i]['P'];
	}
}
if ($arNavParams){
	$arAdditionalData['NAV_CHAIN'] = $APPLICATION->GetNavChain($arNavParams[0], $arNavParams[1], $arNavParams[2], $arNavParams[3], $arNavParams[4]);
}
?>
<script type="text/javascript">
	if(!$('.js_seo_title').length)
		$('<span class="js_seo_title" style="display:none;"></span>').appendTo($('body'));
	BX.addCustomEvent(window, "onAjaxSuccess", function(e){
		var arAjaxPageData = <?=CUtil::PhpToJSObject($arAdditionalData, true, true, true);?>;

		//set title from offers
		if(typeof ItemObj == 'object' && Object.keys(ItemObj).length)
		{
			if('TITLE' in ItemObj && ItemObj.TITLE)
			{
				arAjaxPageData.TITLE = ItemObj.TITLE;
				arAjaxPageData.WINDOW_TITLE = ItemObj.WINDOW_TITLE;
			}
		}

		if (arAjaxPageData.TITLE)
			$('h1').html(arAjaxPageData.TITLE);
		if (arAjaxPageData.WINDOW_TITLE || arAjaxPageData.TITLE)
		{
			$('.js_seo_title').html(arAjaxPageData.WINDOW_TITLE || arAjaxPageData.TITLE); //seo fix for spec symbol
			BX.ajax.UpdateWindowTitle($('.js_seo_title').html());
		}

		if (arAjaxPageData.NAV_CHAIN)
			BX.ajax.UpdatePageNavChain(arAjaxPageData.NAV_CHAIN);
		$('.catalog_detail input[data-sid="PRODUCT_NAME"]').attr('value', $('h1').html());
	});
</script>
<?/*fix title after ajax form end*/?>
<?$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/jquery.history.js');?>