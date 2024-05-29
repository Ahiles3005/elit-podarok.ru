<?
use Bitrix\Main\Loader;

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

CBitrixComponent::includeComponentClass("bitrix:catalog.smart.filter");

class CBitrixCatalogSmartFilterCustom extends CBitrixCatalogSmartFilter
{
	public function makeSmartUrl($url, $apply, $checkedControlId = false)
	{
		$smartParts = array();

		if ($apply)
		{
			foreach($this->arResult["ITEMS"] as $PID => $arItem)
			{
				$smartPart = array();
				//Prices
				if ($arItem["PRICE"])
				{
					if (strlen($arItem["VALUES"]["MIN"]["HTML_VALUE"]) > 0)
						$smartPart["from"] = $arItem["VALUES"]["MIN"]["HTML_VALUE"];
					if (strlen($arItem["VALUES"]["MAX"]["HTML_VALUE"]) > 0)
						$smartPart["to"] = $arItem["VALUES"]["MAX"]["HTML_VALUE"];
				}

				if ($smartPart)
				{
					array_unshift($smartPart, "price-".$arItem["URL_ID"]);

					$smartParts[] = $smartPart;
				}
			}

			foreach($this->arResult["ITEMS"] as $PID => $arItem)
			{
				$smartPart = array();
				if ($arItem["PRICE"])
					continue;

				//Numbers && calendar == ranges
				if (
					$arItem["PROPERTY_TYPE"] == "N"
					|| $arItem["DISPLAY_TYPE"] == "U"
				)
				{
					if (strlen($arItem["VALUES"]["MIN"]["HTML_VALUE"]) > 0)
						$smartPart["from"] = $arItem["VALUES"]["MIN"]["HTML_VALUE"];
					if (strlen($arItem["VALUES"]["MAX"]["HTML_VALUE"]) > 0)
						$smartPart["to"] = $arItem["VALUES"]["MAX"]["HTML_VALUE"];
				}
				else
				{
					foreach($arItem["VALUES"] as $key => $ar)
					{
						if (
							(
								$ar["CHECKED"]
								|| $ar["CONTROL_ID"] === $checkedControlId
							)
							&& strlen($ar["URL_ID"])
						)
						{
							$smartPart[] = $ar["URL_ID"];
						}
					}
				}

				if ($smartPart)
				{
					if ($arItem["CODE"])
						array_unshift($smartPart, toLower($arItem["CODE"]));
					else
						array_unshift($smartPart, $arItem["ID"]);

					$smartParts[] = $smartPart;
				}
			}
		}

		if (!$smartParts)
			$smartParts[] = array("clear");
		
		$filter_url = str_replace("#SMART_FILTER_PATH#", implode("/", $this->encodeSmartParts($smartParts)), $url);
		$filter_url = str_replace("#FILTER#", implode("/", $this->encodeSmartParts($smartParts)), $filter_url);

		return $filter_url;
	}
}
