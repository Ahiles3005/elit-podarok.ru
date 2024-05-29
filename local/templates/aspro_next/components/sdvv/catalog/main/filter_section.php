<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>
<?$this->setFrameMode(true);
//echo "<pre>";print_r($arResult);echo "</pre>";?>
<?ob_start()?>
<?$APPLICATION->IncludeComponent(
	"sdvv:catalog.smart.filter",
	($arParams["AJAX_FILTER_CATALOG"]=="Y" ? "main_ajax" : "main"),
	Array(
		"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
		"IBLOCK_ID" => $arParams["IBLOCK_ID"],
		"AJAX_FILTER_FLAG" => $isAjaxFilter,
		"SECTION_ID" => '',
		"FILTER_NAME" => $arParams["FILTER_NAME"],
		"PRICE_CODE" => ($arParams["USE_FILTER_PRICE"] == 'Y' ? $arParams["FILTER_PRICE_CODE"] : $arParams["PRICE_CODE"]),
		"CACHE_TYPE" => $arParams["CACHE_TYPE"],
		"CACHE_TIME" => $arParams["CACHE_TIME"],
		"CACHE_NOTES" => "",
		"CACHE_GROUPS" => '',
		"SAVE_IN_SESSION" => "N",
		"XML_EXPORT" => "Y",
		"SECTION_TITLE" => "NAME",
		"SECTION_DESCRIPTION" => "DESCRIPTION",
		"SHOW_HINTS" => $arParams["SHOW_HINTS"],
		'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
		'CURRENCY_ID' => $arParams['CURRENCY_ID'],
		'DISPLAY_ELEMENT_COUNT' => $arParams['DISPLAY_ELEMENT_COUNT'],
		"INSTANT_RELOAD" => "Y",
		"VIEW_MODE" => strtolower($arTheme["FILTER_VIEW"]["VALUE"]),
		"SEF_MODE" => "Y",
		"SEF_RULE" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["old_smart_filter"],
		"SMART_FILTER_PATH" => $arResult["VARIABLES"]["FILTER"],
		"HIDE_NOT_AVAILABLE" => $arParams["HIDE_NOT_AVAILABLE"],
		'SHOW_ALL_WO_SECTION'=>'Y',
	),
	$component);
?>

<?$html=ob_get_clean();?>
<?$APPLICATION->AddViewContent('left_menu', $html);?>

<?
unset($GLOBALS[$arParams["FILTER_NAME"]]['FACET_OPTIONS']);
?>

    <div class="seo_block">
		<?$APPLICATION->ShowViewContent('sotbit_seometa_top_desc');?>
		<?$APPLICATION->ShowViewContent('sotbit_seometa_add_desc');?>
    </div>

    <div class="right_block1 clearfix catalog <?=strtolower($arTheme["FILTER_VIEW"]["VALUE"]);?>" id="right_block_ajax">

        <div class="inner_wrapper">

			<?if (empty($GLOBALS[$arParams["FILTER_NAME"]])) {?>
                <div class="no_goods catalog_block_view">
                    <div class="no_products">
                        <div class="wrap_text_empty">
                            <span class="big_text">К сожалению, раздел пуст</span><br>
                            <span class="middle_text">В данный момент нет активных товаров</span>
                        </div>
                    </div>
                </div>
			<?} else {?>

				<?
				__IncludeLang($_SERVER["DOCUMENT_ROOT"].'/bitrix/templates/aspro_next//components/bitrix/catalog/main/lang/ru/section.php');
				@include_once($_SERVER["DOCUMENT_ROOT"].'/bitrix/templates/aspro_next/components/bitrix/catalog/main/sort.php');
				?>
				<?
				if ($display == 'list') {
					$display = 'display_lister';
				}
				?>
                <div class="ajax_load <?=$display;?>">
					<?$APPLICATION->IncludeComponent(
						"bitrix:catalog.section",
						$template,
						array(
							"AJAX_REQUEST" => 'N',
							"ACTION_VARIABLE" => "action",
							"ADD_PICT_PROP" => "-",
							"ADD_PROPERTIES_TO_BASKET" => "Y",
							"ADD_SECTIONS_CHAIN" => "N",
							"ADD_TO_BASKET_ACTION" => "ADD",
							"AJAX_MODE" => "N",
							"AJAX_OPTION_ADDITIONAL" => "",
							"AJAX_OPTION_HISTORY" => "N",
							"AJAX_OPTION_JUMP" => "N",
							"AJAX_OPTION_STYLE" => "Y",
							"BACKGROUND_IMAGE" => "-",
							"BASKET_URL" => "/personal/basket.php",
							"BROWSER_TITLE" => "-",
							"CACHE_FILTER" => "N",
							"CACHE_GROUPS" => "Y",
							"CACHE_TIME" => "36000000",
							"CACHE_TYPE" => "N",
							"COMPATIBLE_MODE" => "Y",
							"CONVERT_CURRENCY" => "Y",
							"CUSTOM_FILTER" => "",
							"DETAIL_URL" => "",
							"DISABLE_INIT_JS_IN_COMPONENT" => "N",
							"DISPLAY_BOTTOM_PAGER" => "Y",
							"DISPLAY_COMPARE" => "Y",
							"DISPLAY_TOP_PAGER" => "N",
							"ELEMENT_SORT_FIELD2" => "CATALOG_QUANTITY",
							"ELEMENT_SORT_ORDER2" => "asc",
							"ELEMENT_SORT_FIELD" => $sort,
							"ELEMENT_SORT_ORDER" => $sort_order,
							"ENLARGE_PRODUCT" => "STRICT",
							"FILTER_NAME" => $arParams["FILTER_NAME"],
							"HIDE_NOT_AVAILABLE" => "L",
							"HIDE_NOT_AVAILABLE_OFFERS" => "L",
							"IBLOCK_ID" => "26",
							"IBLOCK_TYPE" => "aspro_next_catalog",
							"INCLUDE_SUBSECTIONS" => "N",
							"LABEL_PROP" => "",
							"LAZY_LOAD" => "N",
							"LINE_ELEMENT_COUNT" => "4",
							"LOAD_ON_SCROLL" => "N",
							"MESSAGE_404" => "",
							"MESS_BTN_ADD_TO_BASKET" => "В корзину",
							"MESS_BTN_BUY" => "Купить",
							"MESS_BTN_DETAIL" => "Подробнее",
							"MESS_BTN_SUBSCRIBE" => "Подписаться",
							"MESS_NOT_AVAILABLE" => "Нет в наличии",
							"META_DESCRIPTION" => "-",
							"META_KEYWORDS" => "-",
							"OFFERS_CART_PROPERTIES" => array(
							),
							"OFFERS_FIELD_CODE" => array(
								0 => "NAME",
								1 => "",
							),
							"OFFERS_LIMIT" => "5",
							"OFFERS_PROPERTY_CODE" => array(
								0 => "",
								1 => "",
							),
							"OFFERS_SORT_FIELD" => "sort",
							"OFFERS_SORT_FIELD2" => "id",
							"OFFERS_SORT_ORDER" => "asc",
							"OFFERS_SORT_ORDER2" => "desc",
							"PAGER_BASE_LINK_ENABLE" => "N",
							"PAGER_DESC_NUMBERING" => "N",
							"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
							"PAGER_SHOW_ALL" => "N",
							"PAGER_SHOW_ALWAYS" => "N",
							"PAGER_TEMPLATE" => ".default",
							"PAGER_TITLE" => "Товары",
							"PAGE_ELEMENT_COUNT" => "20",
							"PARTIAL_PRODUCT_PROPERTIES" => "N",
							"PRICE_CODE" => array(
								0 => "Обмен с сайтом",
							),
							"PRICE_VAT_INCLUDE" => "Y",
							"PRODUCT_BLOCKS_ORDER" => "price,props,sku,quantityLimit,quantity,buttons",
							"PRODUCT_DISPLAY_MODE" => "N",
							"PRODUCT_ID_VARIABLE" => "id",
							"PRODUCT_PROPERTIES" => array(
							),
							"PRODUCT_PROPS_VARIABLE" => "prop",
							"PRODUCT_QUANTITY_VARIABLE" => "quantity",
							"PRODUCT_ROW_VARIANTS" => "[{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false}]",
							"PRODUCT_SUBSCRIPTION" => "Y",
							"PROPERTY_CODE" => array(
								0 => "CML2_ARTICLE",
								1 => "",
							),
							"PROPERTY_CODE_MOBILE" => "",
							"RCM_PROD_ID" => $_REQUEST["PRODUCT_ID"],
							"RCM_TYPE" => "personal",
							"SECTION_CODE" => "",
							"SECTION_ID" => "",
							"SECTION_ID_VARIABLE" => "SECTION_ID",
							"SECTION_URL" => "",
							"SECTION_USER_FIELDS" => array(
								0 => "",
								1 => "",
							),
							"SEF_MODE" => "Y",
							"SET_BROWSER_TITLE" => "Y",
							"SET_LAST_MODIFIED" => "N",
							"SET_META_DESCRIPTION" => "Y",
							"SET_META_KEYWORDS" => "Y",
							"SET_STATUS_404" => "Y",
							"SET_TITLE" => "Y",
							"SHOW_404" => "Y",
							"SHOW_ALL_WO_SECTION" => "Y",
							"SHOW_CLOSE_POPUP" => "N",
							"SHOW_DISCOUNT_PERCENT" => "N",
							"SHOW_FROM_SECTION" => "N",
							"SHOW_MAX_QUANTITY" => "N",
							"SHOW_OLD_PRICE" => "N",
							"SHOW_PRICE_COUNT" => "1",
							"SHOW_SLIDER" => "Y",
							"SLIDER_INTERVAL" => "3000",
							"SLIDER_PROGRESS" => "N",
							"TEMPLATE_THEME" => "blue",
							"USE_ENHANCED_ECOMMERCE" => "N",
							"USE_MAIN_ELEMENT_SECTION" => "Y",
							"USE_PRICE_COUNT" => "Y",
							"USE_PRODUCT_QUANTITY" => "N",
							"COMPONENT_TEMPLATE" => "catalog_block",
							"CURRENCY_ID" => "RUB",
							"COMPARE_PATH" => "",

							"SEF_FOLDER" => "/catalog/",
							"USE_ELEMENT_COUNTER" => "Y",
							"USE_FILTER" => "Y",
							"FILTER_FIELD_CODE" => array(
								0 => "",
								1 => "",
							),
							"FILTER_PROPERTY_CODE" => array(
								0 => "VES",
								1 => "INKRUSTATSIYA_2",
								2 => "KOMU",
								3 => "VYSOTA_KOROLYA",
								4 => "KOMPLEKTATSIYA_2",
								5 => "POVOD",
								6 => "VYSOTA_PESHKI",
								7 => "MATERIALY_3",
								8 => "PROFESSII",
								9 => "DIAMETR_OSNOVANIYA",
								10 => "MODEL_3",
								11 => "TIP_PODARKA",
								12 => "CML2_ARTICLE",
								13 => "CML2_BASE_UNIT",
								14 => "KOLICHESTVO_FISHEK",
								15 => "OPTICHESKIE_KHARAKTERISTIKI",
								16 => "CML2_MANUFACTURER",
								17 => "CML2_TRAITS",
								18 => "CML2_TAXES",
								19 => "CML2_ATTRIBUTES",
								20 => "CML2_BAR_CODE",
								21 => "KOMPLEKTATSIYA",
								22 => "RAZMER_3",
								23 => "KOSTI",
								24 => "TEKHNIKA_ISPOLNENIYA_3",
								25 => "MATERIALY",
								26 => "UPAKOVKA_3",
								27 => "VES_4",
								28 => "MODEL",
								29 => "VYSOTA",
								30 => "POLE_DLYA_SHASHEK",
								31 => "DIAMETR_1",
								32 => "PROIZVODITEL",
								33 => "INKRUSTATSIYA_3",
								34 => "RAZMER",
								35 => "KOMPLEKTATSIYA_3",
								36 => "RAZMER_V_UPAKOVKE",
								37 => "MATERIALY_4",
								38 => "RAZMER_KLETKI",
								39 => "MODEL_4",
								40 => "STRANA_PROIZVODITEL",
								41 => "OBRAMLENIE_1",
								42 => "TEKHNIKA_ISPOLNENIYA",
								43 => "OBEM_1",
								44 => "UPAKOVKA",
								45 => "VES_1",
								46 => "PROIZVODITEL_2",
								47 => "DIAMETR",
								48 => "RAZMER_4",
								49 => "INKRUSTATSIYA",
								50 => "TEKHNIKA_ISPOLNENIYA_4",
								51 => "KREPLENIE",
								52 => "UPAKOVKA_4",
								53 => "VES_5",
								54 => "MATERIALY_1",
								55 => "IZDATELSTVO",
								56 => "MODEL_1",
								57 => "INKRUSTATSIYA_4",
								58 => "UPAKOVKA_1",
								59 => "KOLICHESTVO_STRANITS",
								60 => "OBRAMLENIE",
								61 => "MATERIALY_5",
								62 => "OBEM",
								63 => "MODEL_5",
								64 => "RAZMER_1",
								65 => "RAZMER_5",
								66 => "RAMA",
								67 => "TEKHNIKA_ISPOLNENIYA_1",
								68 => "TEKHNIKA_ISPOLNENIYA_5",
								69 => "VES_2",
								70 => "UPAKOVKA_5",
								71 => "VID_STALI",
								72 => "FORMAT_KNIGI",
								73 => "VES_6",
								74 => "DLINA_KLINKA",
								75 => "DIAMETR_2",
								76 => "DLINA_NOZHA",
								77 => "DLINA_RUKOYATI",
								78 => "KREPLENIE_1",
								79 => "INKRUSTATSIYA_1",
								80 => "MATERIALY_6",
								81 => "KOMPLEKTATSIYA_1",
								82 => "MODEL_6",
								83 => "MARKA_STALI",
								84 => "OBRAMLENIE_2",
								85 => "MATERIALY_2",
								86 => "RAZMER_6",
								87 => "MODEL_2",
								88 => "RAMA_1",
								89 => "PROIZVODITEL_1",
								90 => "TEKHNIKA_ISPOLNENIYA_6",
								91 => "RAZMER_2",
								92 => "UPAKOVKA_6",
								93 => "ARTIKUL_RAZMERA",
								94 => "STRANA_PROIZVODITEL_1",
								95 => "TVERDOST_STALI_HRC",
								96 => "TSVET",
								97 => "TEKHNIKA_ISPOLNENIYA_2",
								98 => "TOLSHCHINA_KLINKA",
								99 => "TOLSHCHINA_OBUKHA",
								100 => "TOLSHCHINA_RUKOYATI",
								101 => "UPAKOVKA_2",
								102 => "SHIRINA_KLINKA",
								103 => "VES_3",
								104 => "IN_STOCK",
								105 => "",
							),
							"FILTER_PRICE_CODE" => array(
								0 => "Обмен с сайтом",
							),
							"FILTER_OFFERS_FIELD_CODE" => array(
								0 => "NAME",
								1 => "",
							),
							"FILTER_OFFERS_PROPERTY_CODE" => array(
								0 => "",
								1 => "COLOR",
								2 => "CML2_LINK",
								3 => "",
							),
							"USE_REVIEW" => "Y",
							"MESSAGES_PER_PAGE" => "10",
							"USE_CAPTCHA" => "Y",
							"REVIEW_AJAX_POST" => "Y",
							"PATH_TO_SMILE" => "/bitrix/images/forum/smile/",
							"FORUM_ID" => "1",
							"URL_TEMPLATES_READ" => "",
							"SHOW_LINK_TO_FORUM" => "Y",
							"POST_FIRST_MESSAGE" => "N",
							"USE_COMPARE" => "Y",
							"COMPARE_NAME" => "CATALOG_COMPARE_LIST",
							"COMPARE_FIELD_CODE" => array(
								0 => "NAME",
								1 => "TAGS",
								2 => "SORT",
								3 => "PREVIEW_PICTURE",
								4 => "",
							),
							"COMPARE_PROPERTY_CODE" => array(
								0 => "CML2_ARTICLE",
								1 => "CML2_BASE_UNIT",
								2 => "CML2_MANUFACTURER",
								3 => "BRAND",
								4 => "PROP_2033",
								5 => "COLOR_REF2",
								6 => "PROP_159",
								7 => "PROP_2052",
								8 => "PROP_2027",
								9 => "PROP_2053",
								10 => "PROP_2083",
								11 => "PROP_2049",
								12 => "PROP_2026",
								13 => "PROP_2044",
								14 => "PROP_162",
								15 => "PROP_2065",
								16 => "PROP_2054",
								17 => "PROP_2017",
								18 => "PROP_2055",
								19 => "PROP_2069",
								20 => "PROP_2062",
								21 => "PROP_2061",
								22 => "",
							),
							"COMPARE_OFFERS_FIELD_CODE" => array(
								0 => "NAME",
								1 => "PREVIEW_PICTURE",
								2 => "",
							),
							"COMPARE_OFFERS_PROPERTY_CODE" => array(
								0 => "",
								1 => "ARTICLE",
								2 => "VOLUME",
								3 => "SIZES",
								4 => "COLOR_REF",
								5 => "",
							),
							"COMPARE_ELEMENT_SORT_FIELD" => "shows",
							"COMPARE_ELEMENT_SORT_ORDER" => "asc",
							"DISPLAY_ELEMENT_SELECT_BOX" => "N",
							"PRICE_VAT_SHOW_VALUE" => "N",
							"SHOW_TOP_ELEMENTS" => "Y",
							"SECTION_COUNT_ELEMENTS" => "Y",
							"SECTION_TOP_DEPTH" => "2",
							"SECTIONS_LIST_PREVIEW_PROPERTY" => "DESCRIPTION",
							"SHOW_SECTION_LIST_PICTURES" => "Y",
							"LIST_PROPERTY_CODE" => array(
								0 => "CML2_ARTICLE",
								1 => "BRAND",
								2 => "PROP_2033",
								3 => "COLOR_REF2",
								4 => "PROP_159",
								5 => "PROP_2052",
								6 => "PROP_2027",
								7 => "PROP_2053",
								8 => "PROP_2083",
								9 => "PROP_2049",
								10 => "PROP_2026",
								11 => "PROP_2044",
								12 => "PROP_162",
								13 => "PROP_2065",
								14 => "PROP_2054",
								15 => "PROP_2017",
								16 => "PROP_2055",
								17 => "PROP_2069",
								18 => "PROP_2062",
								19 => "PROP_2061",
								20 => "CML2_LINK",
								21 => "",
							),
							"LIST_META_KEYWORDS" => "-",
							"LIST_META_DESCRIPTION" => "-",
							"LIST_BROWSER_TITLE" => "-",
							"LIST_OFFERS_FIELD_CODE" => array(
								0 => "NAME",
								1 => "CML2_LINK",
								2 => "DETAIL_PAGE_URL",
								3 => "",
							),
							"LIST_OFFERS_PROPERTY_CODE" => array(
								0 => "",
								1 => "ARTICLE",
								2 => "VOLUME",
								3 => "SIZES",
								4 => "COLOR_REF",
								5 => "",
							),
							"LIST_OFFERS_LIMIT" => "10",
							"SORT_BUTTONS" => array(
								0 => "POPULARITY",
								1 => "NAME",
								2 => "PRICE",
							),
							"SORT_PRICES" => "REGION_PRICE",
							"DEFAULT_LIST_TEMPLATE" => "block",
							"SECTION_DISPLAY_PROPERTY" => "",
							"LIST_DISPLAY_POPUP_IMAGE" => "Y",
							"SECTION_PREVIEW_PROPERTY" => "DESCRIPTION",
							"SHOW_SECTION_PICTURES" => "Y",
							"SHOW_SECTION_SIBLINGS" => "Y",
							"DETAIL_PROPERTY_CODE" => array(
								0 => "VES",
								1 => "INKRUSTATSIYA_2",
								2 => "VYSOTA_KOROLYA",
								3 => "KOMPLEKTATSIYA_2",
								4 => "VYSOTA_PESHKI",
								5 => "MATERIALY_3",
								6 => "DIAMETR_OSNOVANIYA",
								7 => "MODEL_3",
								8 => "CML2_ARTICLE",
								9 => "KOLICHESTVO_FISHEK",
								10 => "OPTICHESKIE_KHARAKTERISTIKI",
								11 => "CML2_ATTRIBUTES",
								12 => "KOMPLEKTATSIYA",
								13 => "RAZMER_3",
								14 => "KOSTI",
								15 => "TEKHNIKA_ISPOLNENIYA_3",
								16 => "MATERIALY",
								17 => "UPAKOVKA_3",
								18 => "VES_4",
								19 => "MODEL",
								20 => "VYSOTA",
								21 => "POLE_DLYA_SHASHEK",
								22 => "DIAMETR_1",
								23 => "PROIZVODITEL",
								24 => "INKRUSTATSIYA_3",
								25 => "RAZMER",
								26 => "KOMPLEKTATSIYA_3",
								27 => "RAZMER_V_UPAKOVKE",
								28 => "MATERIALY_4",
								29 => "RAZMER_KLETKI",
								30 => "MODEL_4",
								31 => "STRANA_PROIZVODITEL",
								32 => "OBRAMLENIE_1",
								33 => "TEKHNIKA_ISPOLNENIYA",
								34 => "OBEM_1",
								35 => "UPAKOVKA",
								36 => "VES_1",
								37 => "PROIZVODITEL_2",
								38 => "DIAMETR",
								39 => "RAZMER_4",
								40 => "INKRUSTATSIYA",
								41 => "TEKHNIKA_ISPOLNENIYA_4",
								42 => "KREPLENIE",
								43 => "UPAKOVKA_4",
								44 => "VES_5",
								45 => "MATERIALY_1",
								46 => "IZDATELSTVO",
								47 => "MODEL_1",
								48 => "INKRUSTATSIYA_4",
								49 => "UPAKOVKA_1",
								50 => "KOLICHESTVO_STRANITS",
								51 => "OBRAMLENIE",
								52 => "MATERIALY_5",
								53 => "OBEM",
								54 => "MODEL_5",
								55 => "RAZMER_1",
								56 => "RAZMER_5",
								57 => "RAMA",
								58 => "TEKHNIKA_ISPOLNENIYA_1",
								59 => "TEKHNIKA_ISPOLNENIYA_5",
								60 => "VES_2",
								61 => "UPAKOVKA_5",
								62 => "VID_STALI",
								63 => "FORMAT_KNIGI",
								64 => "VES_6",
								65 => "DLINA_KLINKA",
								66 => "DIAMETR_2",
								67 => "DLINA_NOZHA",
								68 => "DLINA_RUKOYATI",
								69 => "KREPLENIE_1",
								70 => "INKRUSTATSIYA_1",
								71 => "MATERIALY_6",
								72 => "KOMPLEKTATSIYA_1",
								73 => "MODEL_6",
								74 => "MARKA_STALI",
								75 => "OBRAMLENIE_2",
								76 => "MATERIALY_2",
								77 => "RAZMER_6",
								78 => "MODEL_2",
								79 => "RAMA_1",
								80 => "PROIZVODITEL_1",
								81 => "TEKHNIKA_ISPOLNENIYA_6",
								82 => "RAZMER_2",
								83 => "UPAKOVKA_6",
								84 => "ARTIKUL_RAZMERA",
								85 => "STRANA_PROIZVODITEL_1",
								86 => "TVERDOST_STALI_HRC",
								87 => "TSVET",
								88 => "TEKHNIKA_ISPOLNENIYA_2",
								89 => "TOLSHCHINA_KLINKA",
								90 => "TOLSHCHINA_OBUKHA",
								91 => "TOLSHCHINA_RUKOYATI",
								92 => "UPAKOVKA_2",
								93 => "SHIRINA_KLINKA",
								94 => "VES_3",
								95 => "BRAND",
								96 => "VIDEO_YOUTUBE",
								97 => "PROP_2033",
								98 => "COLOR_REF2",
								99 => "PROP_159",
								100 => "PROP_2052",
								101 => "PROP_2027",
								102 => "PROP_2053",
								103 => "PROP_2083",
								104 => "PROP_2049",
								105 => "PROP_2026",
								106 => "PROP_2044",
								107 => "PROP_162",
								108 => "PROP_2065",
								109 => "PROP_2054",
								110 => "PROP_2017",
								111 => "PROP_2055",
								112 => "PROP_2069",
								113 => "PROP_2062",
								114 => "PROP_2061",
								115 => "RECOMMEND",
								116 => "NEW",
								117 => "STOCK",
								118 => "VIDEO",
								119 => "",
							),
							"DETAIL_META_KEYWORDS" => "-",
							"DETAIL_META_DESCRIPTION" => "-",
							"DETAIL_BROWSER_TITLE" => "-",
							"DETAIL_OFFERS_FIELD_CODE" => array(
								0 => "NAME",
								1 => "PREVIEW_PICTURE",
								2 => "DETAIL_PICTURE",
								3 => "DETAIL_PAGE_URL",
								4 => "",
							),
							"DETAIL_OFFERS_PROPERTY_CODE" => array(
								0 => "",
								1 => "ARTICLE",
								2 => "VOLUME",
								3 => "SIZES",
								4 => "COLOR_REF",
								5 => "FRAROMA",
								6 => "SPORT",
								7 => "VLAGOOTVOD",
								8 => "AGE",
								9 => "RUKAV",
								10 => "KAPUSHON",
								11 => "FRCOLLECTION",
								12 => "FRLINE",
								13 => "FRFITIL",
								14 => "FRMADEIN",
								15 => "FRELITE",
								16 => "TALL",
								17 => "FRFAMILY",
								18 => "FRSOSTAVCANDLE",
								19 => "FRTYPE",
								20 => "FRFORM",
								21 => "",
							),
							"PROPERTIES_DISPLAY_LOCATION" => "DESCRIPTION",
							"SHOW_BRAND_PICTURE" => "Y",
							"SHOW_ASK_BLOCK" => "Y",
							"ASK_FORM_ID" => "2",
							"SHOW_ADDITIONAL_TAB" => "Y",
							"PROPERTIES_DISPLAY_TYPE" => "TABLE",
							"SHOW_KIT_PARTS" => "Y",
							"SHOW_KIT_PARTS_PRICES" => "Y",
							"LINK_IBLOCK_TYPE" => "aspro_next_content",
							"LINK_IBLOCK_ID" => "",
							"LINK_PROPERTY_SID" => "",
							"LINK_ELEMENTS_URL" => "link.php?PARENT_ELEMENT_ID=#ELEMENT_ID#",
							"USE_ALSO_BUY" => "Y",
							"ALSO_BUY_ELEMENT_COUNT" => "5",
							"ALSO_BUY_MIN_BUYES" => "2",
							"USE_STORE" => "N",
							"USE_STORE_PHONE" => "Y",
							"USE_STORE_SCHEDULE" => "Y",
							"USE_MIN_AMOUNT" => "N",
							"MIN_AMOUNT" => "10",
							"STORE_PATH" => "/contacts/stores/#store_id#/",
							"MAIN_TITLE" => "Наличие на складах",
							"MAX_AMOUNT" => "20",
							"USE_ONLY_MAX_AMOUNT" => "Y",
							"IBLOCK_STOCK_ID" => "19",
							"SHOW_QUANTITY" => "Y",
							"SHOW_MEASURE" => "Y",
							"SHOW_QUANTITY_COUNT" => "Y",
							"USE_RATING" => "Y",
							"DISPLAY_WISH_BUTTONS" => "Y",
							"DEFAULT_COUNT" => "1",
							"SHOW_HINTS" => "Y",
							"ADD_ELEMENT_CHAIN" => "Y",
							"DETAIL_CHECK_SECTION_ID_VARIABLE" => "N",
							"STORES" => array(
								0 => "",
								1 => "",
							),
							"USER_FIELDS" => array(
								0 => "",
								1 => "",
							),
							"FIELDS" => array(
								0 => "",
								1 => "",
							),
							"SHOW_EMPTY_STORE" => "Y",
							"SHOW_GENERAL_STORE_INFORMATION" => "N",
							"TOP_ELEMENT_COUNT" => "8",
							"TOP_LINE_ELEMENT_COUNT" => "4",
							"TOP_ELEMENT_SORT_FIELD" => "shows",
							"TOP_ELEMENT_SORT_ORDER" => "asc",
							"TOP_ELEMENT_SORT_FIELD2" => "shows",
							"TOP_ELEMENT_SORT_ORDER2" => "asc",
							"TOP_PROPERTY_CODE" => array(
								0 => "",
								1 => "",
							),
							"DETAIL_SET_CANONICAL_URL" => "Y",
							"SHOW_DEACTIVATED" => "N",
							"TOP_OFFERS_FIELD_CODE" => array(
								0 => "ID",
								1 => "",
							),
							"TOP_OFFERS_PROPERTY_CODE" => array(
								0 => "",
								1 => "",
							),
							"TOP_OFFERS_LIMIT" => "10",
							"SECTION_TOP_BLOCK_TITLE" => "Лучшие предложения",
							"OFFER_TREE_PROPS" => array(
							),
							"USE_BIG_DATA" => "Y",
							"BIG_DATA_RCM_TYPE" => "personal",
							"VIEWED_ELEMENT_COUNT" => "20",
							"VIEWED_BLOCK_TITLE" => "Ранее вы смотрели",
							"ELEMENT_SORT_FIELD_BOX" => "name",
							"ELEMENT_SORT_ORDER_BOX" => "asc",
							"ELEMENT_SORT_FIELD_BOX2" => "id",
							"ELEMENT_SORT_ORDER_BOX2" => "desc",
							"OFFER_ADD_PICT_PROP" => "MORE_PHOTO",
							"DETAIL_ADD_DETAIL_TO_SLIDER" => "Y",
							"SKU_DETAIL_ID" => "oid",
							"AJAX_FILTER_CATALOG" => "N",
							"SECTION_BACKGROUND_IMAGE" => "-",
							"DETAIL_BACKGROUND_IMAGE" => "-",
							"DISPLAY_ELEMENT_SLIDER" => "10",
							"SHOW_ONE_CLICK_BUY" => "Y",
							"USE_GIFTS_DETAIL" => "Y",
							"USE_GIFTS_SECTION" => "Y",
							"USE_GIFTS_MAIN_PR_SECTION_LIST" => "Y",
							"GIFTS_DETAIL_PAGE_ELEMENT_COUNT" => "8",
							"GIFTS_DETAIL_HIDE_BLOCK_TITLE" => "N",
							"GIFTS_DETAIL_BLOCK_TITLE" => "Выберите один из подарков",
							"GIFTS_DETAIL_TEXT_LABEL_GIFT" => "Подарок",
							"GIFTS_SECTION_LIST_PAGE_ELEMENT_COUNT" => "8",
							"GIFTS_SECTION_LIST_HIDE_BLOCK_TITLE" => "N",
							"GIFTS_SECTION_LIST_BLOCK_TITLE" => "Подарки к товарам этого раздела",
							"GIFTS_SECTION_LIST_TEXT_LABEL_GIFT" => "Подарок",
							"GIFTS_SHOW_DISCOUNT_PERCENT" => "Y",
							"GIFTS_SHOW_OLD_PRICE" => "Y",
							"GIFTS_SHOW_NAME" => "Y",
							"GIFTS_SHOW_IMAGE" => "Y",
							"GIFTS_MESS_BTN_BUY" => "Выбрать",
							"GIFTS_MAIN_PRODUCT_DETAIL_PAGE_ELEMENT_COUNT" => "8",
							"GIFTS_MAIN_PRODUCT_DETAIL_HIDE_BLOCK_TITLE" => "N",
							"GIFTS_MAIN_PRODUCT_DETAIL_BLOCK_TITLE" => "Выберите один из товаров, чтобы получить подарок",
							"OFFER_HIDE_NAME_PROPS" => "N",
							"DETAIL_SET_VIEWED_IN_COMPONENT" => "N",
							"SECTION_PREVIEW_DESCRIPTION" => "Y",
							"SECTIONS_LIST_PREVIEW_DESCRIPTION" => "Y",
							"SALE_STIKER" => "SALE_TEXT",
							"SHOW_DISCOUNT_TIME" => "Y",
							"SHOW_RATING" => "Y",
							"COMPOSITE_FRAME_MODE" => "A",
							"COMPOSITE_FRAME_TYPE" => "AUTO",
							"DETAIL_OFFERS_LIMIT" => "0",
							"DETAIL_EXPANDABLES_TITLE" => "Аксессуары",
							"DETAIL_ASSOCIATED_TITLE" => "Похожие товары",
							"DETAIL_PICTURE_MODE" => "MAGNIFIER",
							"SHOW_UNABLE_SKU_PROPS" => "Y",
							"DETAIL_STRICT_SECTION_CHECK" => "Y",
							"COMMON_SHOW_CLOSE_POPUP" => "N",
							"MESS_BTN_COMPARE" => "Сравнение",
							"SIDEBAR_SECTION_SHOW" => "Y",
							"SIDEBAR_DETAIL_SHOW" => "N",
							"SIDEBAR_PATH" => "",
							"USE_SALE_BESTSELLERS" => "Y",
							"FILTER_VIEW_MODE" => "VERTICAL",
							"FILTER_HIDE_ON_MOBILE" => "N",
							"INSTANT_RELOAD" => "N",
							"COMPARE_POSITION_FIXED" => "Y",
							"COMPARE_POSITION" => "top left",
							"USE_RATIO_IN_RANGES" => "Y",
							"USE_COMMON_SETTINGS_BASKET_POPUP" => "N",
							"COMMON_ADD_TO_BASKET_ACTION" => "ADD",
							"TOP_ADD_TO_BASKET_ACTION" => "ADD",
							"SECTION_ADD_TO_BASKET_ACTION" => "ADD",
							"DETAIL_ADD_TO_BASKET_ACTION" => array(
								0 => "BUY",
							),
							"DETAIL_ADD_TO_BASKET_ACTION_PRIMARY" => array(
								0 => "BUY",
							),
							"TOP_PROPERTY_CODE_MOBILE" => "",
							"TOP_VIEW_MODE" => "SECTION",
							"TOP_PRODUCT_BLOCKS_ORDER" => "price,props,sku,quantityLimit,quantity,buttons,compare",
							"TOP_PRODUCT_ROW_VARIANTS" => "[{'VARIANT':'3','BIG_DATA':false},{'VARIANT':'3','BIG_DATA':false}]",
							"TOP_ENLARGE_PRODUCT" => "STRICT",
							"TOP_SHOW_SLIDER" => "Y",
							"TOP_SLIDER_INTERVAL" => "3000",
							"TOP_SLIDER_PROGRESS" => "N",
							"SECTIONS_VIEW_MODE" => "LIST",
							"SECTIONS_SHOW_PARENT_NAME" => "Y",
							"LIST_PROPERTY_CODE_MOBILE" => "",
							"LIST_PRODUCT_BLOCKS_ORDER" => "price,props,sku,quantityLimit,quantity,buttons,compare",
							"LIST_PRODUCT_ROW_VARIANTS" => "[{'VARIANT':'3','BIG_DATA':false},{'VARIANT':'3','BIG_DATA':false},{'VARIANT':'3','BIG_DATA':false},{'VARIANT':'3','BIG_DATA':false},{'VARIANT':'3','BIG_DATA':false}]",
							"LIST_ENLARGE_PRODUCT" => "STRICT",
							"LIST_SHOW_SLIDER" => "Y",
							"LIST_SLIDER_INTERVAL" => "3000",
							"LIST_SLIDER_PROGRESS" => "N",
							"DETAIL_MAIN_BLOCK_PROPERTY_CODE" => "",
							"DETAIL_MAIN_BLOCK_OFFERS_PROPERTY_CODE" => "",
							"DETAIL_USE_VOTE_RATING" => "N",
							"DETAIL_USE_COMMENTS" => "N",
							"DETAIL_BRAND_USE" => "N",
							"DETAIL_DISPLAY_NAME" => "Y",
							"DETAIL_IMAGE_RESOLUTION" => "16by9",
							"DETAIL_PRODUCT_INFO_BLOCK_ORDER" => "sku,props",
							"DETAIL_PRODUCT_PAY_BLOCK_ORDER" => "rating,price,priceRanges,quantityLimit,quantity,buttons",
							"DETAIL_SHOW_SLIDER" => "N",
							"DETAIL_DETAIL_PICTURE_MODE" => array(
								0 => "POPUP",
								1 => "MAGNIFIER",
							),
							"DETAIL_DISPLAY_PREVIEW_TEXT_MODE" => "E",
							"MESS_PRICE_RANGES_TITLE" => "Цены",
							"MESS_DESCRIPTION_TAB" => "Описание",
							"MESS_PROPERTIES_TAB" => "Характеристики",
							"MESS_COMMENTS_TAB" => "Комментарии",
							"DETAIL_DOCS_PROP" => "-",
							"STIKERS_PROP" => "HIT",
							"USE_SHARE" => "Y",
							"TAB_OFFERS_NAME" => "",
							"TAB_DESCR_NAME" => "",
							"TAB_CHAR_NAME" => "",
							"TAB_VIDEO_NAME" => "",
							"TAB_REVIEW_NAME" => "",
							"TAB_FAQ_NAME" => "",
							"TAB_STOCK_NAME" => "",
							"TAB_DOPS_NAME" => "",
							"BLOCK_SERVICES_NAME" => "",
							"BLOCK_DOCS_NAME" => "",
							"CHEAPER_FORM_NAME" => "",
							"DIR_PARAMS" => CNext::GetDirMenuParametrs(__DIR__),
							"SHOW_CHEAPER_FORM" => "Y",
							"LANDING_TITLE" => "Популярные категории",
							"LANDING_SECTION_COUNT" => "7",
							"LANDING_SEARCH_TITLE" => "Похожие запросы",
							"LANDING_SEARCH_COUNT" => "7",
							"SECTIONS_TYPE_VIEW" => "sections_1",
							"SECTION_ELEMENTS_TYPE_VIEW" => "list_elements_1",
							"ELEMENT_TYPE_VIEW" => "FROM_MODULE",
							"SHOW_ARTICLE_SKU" => "Y",
							"SORT_REGION_PRICE" => "BASE",
							"BIGDATA_NORMAL" => "bigdata_1",
							"BIGDATA_EXT" => "bigdata_2",
							"SHOW_MEASURE_WITH_RATIO" => "N",
							"SHOW_DISCOUNT_PERCENT_NUMBER" => "Y",
							"ALT_TITLE_GET" => "SEO",
							"SHOW_COUNTER_LIST" => "Y",
							"SHOW_DISCOUNT_TIME_EACH_SKU" => "N",
							"USER_CONSENT" => "N",
							"USER_CONSENT_ID" => "0",
							"USER_CONSENT_IS_CHECKED" => "Y",
							"USER_CONSENT_IS_LOADED" => "N",
							"SHOW_HOW_BUY" => "Y",
							"TITLE_HOW_BUY" => "Как купить",
							"SHOW_DELIVERY" => "Y",
							"TITLE_DELIVERY" => "Доставка",
							"SHOW_PAYMENT" => "Y",
							"TITLE_PAYMENT" => "Оплата",
							"SHOW_GARANTY" => "Y",
							"TITLE_GARANTY" => "Условия гарантии",
							"USE_FILTER_PRICE" => "N",
							"DISPLAY_ELEMENT_COUNT" => "Y",
							"RESTART" => "N",
							"USE_LANGUAGE_GUESS" => "Y",
							"NO_WORD_LOGIC" => "Y",
							"SHOW_SECTION_DESC" => "Y",
							"TITLE_SLIDER" => "Рекомендуем",
							"VIEW_BLOCK_TYPE" => "N",
							"SHOW_SEND_GIFT" => "Y",
							"SEND_GIFT_FORM_NAME" => "",
							"USE_ADDITIONAL_GALLERY" => "N",
							"BLOCK_LANDINGS_NAME" => "",
							"BLOG_IBLOCK_ID" => "",
							"BLOCK_BLOG_NAME" => "",
							"RECOMEND_COUNT" => "5",
							"VISIBLE_PROP_COUNT" => "4",
							"BUNDLE_ITEMS_COUNT" => "3",
							"STORES_FILTER" => "TITLE",
							"STORES_FILTER_ORDER" => "SORT_ASC",
							"FILE_404" => "",
							"VARIABLE_ALIASES" => array(
								"compare" => array(
									"ACTION_CODE" => "action",
								),
							)
						),
						$component
					);?>
                </div>

			<?}?>
        </div>
		<?$APPLICATION->ShowViewContent('sotbit_seometa_bottom_desc');?>
    </div>

<?if(\Bitrix\Main\Loader::includeModule("sotbit.seometa")):?>
	<?$APPLICATION->IncludeComponent(
		"sotbit:seo.meta",
		".default",
		array(
			"FILTER_NAME" => $arParams["FILTER_NAME"],
			"SECTION_ID" => 2230,
			"CACHE_TYPE" => $arParams["CACHE_TYPE"],
			"CACHE_TIME" => $arParams["CACHE_TIME"],
		)
	);?>
<?endif;?>