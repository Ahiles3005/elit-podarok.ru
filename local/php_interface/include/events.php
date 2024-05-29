<?
// Delete artifacts from resize images
\Bitrix\Main\EventManager::getInstance()->addEventHandler(
    "main",
    "OnAfterResizeImage",
    array(
        "Handlers",
        "checkBadPictures"
    )
);

class Handlers{
    public static function checkBadPictures(
        $file,
        $options,
        &$callbackData,
        &$cacheImageFile,
        &$cacheImageFileTmp,
        &$arImageSize
    ) {
        if (file_exists($cacheImageFileTmp)) {
            if (stripos($cacheImageFileTmp, 'jpg') !== false || stripos($cacheImageFileTmp, 'jpeg') !== false) {
                $maxColor = 251;
                $oldImg = imagecreatefromjpeg($cacheImageFileTmp);
                $sizes = getimagesize($cacheImageFileTmp);
                $newImg = imagecreatetruecolor($sizes[0], $sizes[1]);
                imagecopyresampled($newImg, $oldImg, 0, 0, 0, 0, $sizes[0], $sizes[1], $sizes[0], $sizes[1]);
                $colorWhite = imagecolorallocate($newImg, 255, 255, 255);
                for ($y = 0; $y < ($sizes[1]); ++$y) {
                    for ($x = 0; $x < ($sizes[0]); ++$x) {
                        $colorat = imagecolorat($newImg, $x, $y);
                        $r = ($colorat >> 16) & 0xFF;
                        $g = ($colorat >> 8) & 0xFF;
                        $b = $colorat & 0xFF;

                        // Если цвет пикселя нас не устраивает, заменяем его на белый
                        if (($r >= $maxColor && $g >= $maxColor && $b >= $maxColor)) {
                            imagesetpixel($newImg, $x, $y, $colorWhite);
                        }
                    }
                }
                imagejpeg($newImg, $cacheImageFileTmp, 100);
            }
        }
    }
}

/**
 * Событие после обмана с 1с
 */
Bitrix\Main\EventManager::getInstance()->addEventHandler('catalog', 'OnCompleteCatalogImport1C', 'customCatalogImportStep');
function customCatalogImportStep() {
    /**
     * Обновляем детальные описания товаров
     */
    detailTextFromProp();
}
/**
 * Событие после изменения элемента
 */
Bitrix\Main\EventManager::getInstance()->addEventHandler('iblock', 'OnAfterIBlockElementUpdate', 'CustomOnAfterIBlockElementUpdate');
function CustomOnAfterIBlockElementUpdate(&$arFields) {
    global $noCheckText;
    switch ($arFields['IBLOCK_ID']) {
        //Каталог
        case 26:
            {
                /**
                 * Обновляем детальное описание товара
                 */
                if ($noCheckText != true)
                    detailTextFromProp($arFields['ID']);
            }
            break;
    }
}

/**
 * Событие проверки спама
 */
Bitrix\Main\EventManager::getInstance()->addEventHandler('form', 'onBeforeResultAdd', 'blockSpam');
function blockSpam($WEB_FORM_ID, &$arFields, &$arrVALUES)
{
    global $APPLICATION, $USER;

    // действие обработчика распространяется только на форму с ID=6
    if ($WEB_FORM_ID == 3)
    {
        if (!$USER->IsAuthorized()) {
            if (strpos($arrVALUES["form_textarea_15"], 'http') !== false) {
                global $APPLICATION;
                $APPLICATION->throwException("К сожалению, Ваш комментарий не может быть опубликован.");
                return false;
            }
        }

        return true;
    }
}

/**
 * Событие добавления телефон в шаблон заказа
 */
Bitrix\Main\EventManager::getInstance()->addEventHandler(
    'sale',
    'OnOrderNewSendEmail',
    function($orderId, $eventName, &$arFields){
        $orderProps = \CSaleOrderPropsValue::GetOrderProps($orderId);
        while ($props = $orderProps->fetch())
        {
            if($props['CODE'] == 'PHONE')
                $arFields[$props['CODE']] = $props['VALUE'];
        }
		// выводим список товаров в другом виде
		if(Bitrix\Main\Loader::includeModule("sale")) {
			$strOrderList = "";
			$arBasketItems = array();
			$dbBasketItems = CSaleBasket::GetList(
				array("NAME" => "ASC"),
				array("ORDER_ID" => $orderId),
				false,
				false,
				array("PRODUCT_ID", "ID", "NAME", "QUANTITY", "PRICE", "CURRENCY", "DETAIL_PAGE_URL")
			);
			while($arBasketItem = $dbBasketItems->Fetch())
				$arBasketItems[] = $arBasketItem;
			
			$arBasketItems = getMeasures($arBasketItems);
			$baseLangCurrency = CSaleLang::GetLangCurrency(SITE_ID);
			
			foreach($arBasketItems as $val)
			{
				if (CSaleBasketHelper::isSetItem($val))
					continue;

				$measure = (isset($val["MEASURE_TEXT"])) ? $val["MEASURE_TEXT"] : "шт";
				$strOrderList .= "<a href='" . $val["DETAIL_PAGE_URL"] . "'>" . $val["NAME"] . "</a>";
				$strOrderList .=  " - " . $val["QUANTITY"] . " " . $measure . " x " . SaleFormatCurrency($val["PRICE"], $baseLangCurrency);
				$strOrderList .= "</br>";
			}
			
			$arFields["ORDER_LIST"] = $strOrderList; 
		}
    }
);

Bitrix\Main\EventManager::getInstance()->addEventHandler(
	"main",
	"OnEndBufferContent",
	function (&$content)
	{
		/*
		 * удаляем type='text/css' и type='text/javascript' - тупит гугл серч консоль
		 */
		$content = preg_replace("%[ ]type=[\'\"]text\/(javascript|css)[\'\"]%", "", $content);
		//\bitrix\main\diag\debug::dumpToFile($content);

		/*
		 * сжатие html /вык, глючит аспро
		 */
		/*$search = [
			//'/\<[^\S ]+/s',
			//'/[^\S ]+\</s',
			'/(\s)+/s'
		];
		$replace = [
			//'<',
			//'<',
			'\\1'
		];
		$content = preg_replace($search,$replace,$content);
*/

		/*
		 * уберем жс для бота
		 */
		if(BOT_PAGESPEED){
			$arPatternsToRemove = Array(
				'/<script.+?src=".+?kernel_main\/kernel_main\.js\?\d+"><\/script\>/',
				'/<script.+?src=".+?kernel_main\/kernel_main_v1\.js\?\d+"><\/script\>/',
				'/<script.+?src=".+?template_.+?_v1\.js\?\d+"><\/script\>/',
				'/<script.+?src=".+?bitrix\/js\/main\/core\/core[^"]+"><\/script\>/',
				'/<script.+?>BX\.(setCSSList|setJSList)\(\[.+?\]\).*?<\/script>/',
				'/<script.+?>if\(\!window\.BX\)window\.BX.+?<\/script>/',
				'/<script[^>]+?>\(window\.BX\|\|top\.BX\)\.message[^<]+<\/script>/',
				'/<script.+?src=".+?bitrix\/js\/main\/loadext\/loadext[^"]+"><\/script\>/',
				'/<script.+?src=".+?bitrix\/js\/main\/loadext\/extension[^"]+"><\/script\>/',
			);

			$content = preg_replace($arPatternsToRemove, "", $content);
			$content = preg_replace("/\n{2,}/", "\n\n", $content);
		}
	}
);

// добавляем артикул в поиск по заголовкам
Bitrix\Main\Loader::includeModule("search");
Bitrix\Main\EventManager::getInstance()->addEventHandler('search', 'OnAfterIndexAdd', function($searchContentId, &$arFields){
	if($arFields['MODULE_ID'] === 'iblock' && (int)$arFields['ITEM_ID'] > 0 && $arFields['PARAM2'] == IBLOCK_ID_CATALOG) {
		$arProp = CIBlockElement::GetProperty(
			IBLOCK_ID_CATALOG, 
			$arFields["ITEM_ID"], 
			array("SORT" => "ASC"), 
			array("CODE" => "CML2_ARTICLE")
		)->Fetch();
		if($arProp && $arProp["VALUE"]) {
			CSearch::IndexTitle(
               $arFields["SITE_ID"],
               $searchContentId,
               $arProp["VALUE"]
           );
		}
	}
});