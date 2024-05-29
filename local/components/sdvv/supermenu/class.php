<?php
use Bitrix\Main\Config\Option;
use Sdvv\Supermenu\Options;

class CSuperMenu extends CBitrixComponent {

    public $module_id = 'sdvv_supermenu';

    public function executeComponent()
    {
        $this->arResult['TYPE_MENU'] = $this->getTypeMenu();
        $this->arResult['MAIN_MENU'] = $this->getMainMenu();

        $showCatalog = Option::get($this->module_id, 'show_catalog');
        if ($showCatalog == 'Y') {
            $this->arResult['CATALOG_MENU'] = $this->getCatalogMenu();
        }

        $this->includeComponentTemplate();
        return $this->arResult;
    }

    public function getTypeMenu() {
        global $APPLICATION;
        $arResult = array();
        $typeMenu = Option::get($this->module_id, 'type_menu');
        $obMenu = $APPLICATION->GetMenu($typeMenu, false);
        if (!empty($obMenu)) {
            $arMenu = $obMenu->arMenu;
            foreach ($arMenu as $arMenuItem) {
                $arResult[] = $arMenuItem;
            }
        }
        return $arResult;
    }

    public function getMainMenu() {
        $arResult = array();
        $serializeMainMenu = Option::get($this->module_id, 'MENU');
        $mainMenu = unserialize($serializeMainMenu);

        $iblock = Option::get($this->module_id, 'iblock');
        $arIblockProperties = (new Sdvv\Supermenu\Options)->getIblockProperties($iblock);

        foreach ($mainMenu as $mainMenuKey => $mainMenuItem) {
            $item = array(
                'ID' => $mainMenuItem['ID'],
                'NAME' => $arIblockProperties[$mainMenuItem['ID']],
                'LINK' => '/filter/'
            );
            if (!empty($mainMenuItem['ITEMS'])) {
                $enum = Options::getPropertyEnumFull($mainMenuItem['ID']);
                foreach ($mainMenuItem['ITEMS'] as $arItems) {
                    $url = $enum[$arItems]['PROPERTY_CODE'].'-is-'.$enum[$arItems]['CODE'];
                    $subItem = array(
                        'ID' => $arItems,
                        'NAME' => $enum[$arItems]['NAME'],
                        'LINK' => '/filter/'.$url.'/apply/'
                    );
                    $item['ITEMS'][] = $subItem;
                }
            } elseif (!empty($mainMenuItem['ITEMS_THREE'])) {
                $enum = Options::getPropertyEnumFull($mainMenuItem['ID']);
                foreach ($mainMenuItem['ITEMS_THREE'] as $arThreeItem) {
                    $url = $enum[$arThreeItem['ID']]['PROPERTY_CODE'].'-is-'.$enum[$arThreeItem['ID']]['CODE'];
                    $itemThree = array(
                        'ID' => $arThreeItem['ID'],
                        'NAME' => $enum[$arThreeItem['ID']]['NAME'],
                        'LINK' => '/filter/'.$url.'/apply/'
                    );
                    if (!empty($arThreeItem['ITEMS'])) {
                        foreach ($arThreeItem['ITEMS'] as $arThreeItemItems) {
                            $urlNew = $url.'-or-'.$enum[$arThreeItemItems]['CODE'];
                            $arLastItem = array(
                                'ID' => $arThreeItemItems,
                                'NAME' => $enum[$arThreeItemItems]['NAME'],
                                'LINK' => '/filter/'.$urlNew.'/apply/'
                            );
                            $itemThree['ITEMS'][] = $arLastItem;
                        }
                    }
                    $item['THREE_ITEMS'][] = $itemThree;
                }
            }

            $arResult[$mainMenuKey] = $item;
        }
        return $arResult;
    }

    public function getCatalogMenu() {
        global $APPLICATION;
        $typeMenu = Option::get($this->module_id, 'type_menu');
        $obMenu = $APPLICATION->GetMenu($typeMenu, true);
        if (!empty($obMenu)) {
            $arMenu = $obMenu->arMenu;
            $firstParent = -1;
            $secondParent = -1;
            foreach ($arMenu as $key => $arMenuItem) {
                if ($arMenuItem[3]['FROM_IBLOCK']) {
                    if ($arMenuItem[3]['IS_PARENT'] == 1 && $arMenuItem[3]['DEPTH_LEVEL'] == 1) {
                        $firstParent++;
                        $item = array(
                            'NAME' => $arMenuItem[0],
                            'LINK' => $arMenuItem[1],
                        );
                        $arMenuCatalog[$firstParent] = $item;
                    }
                    if ($arMenuItem[3]['IS_PARENT'] == 1 && $arMenuItem[3]['DEPTH_LEVEL'] == 2) {
                        $secondParent++;
                        $item = array(
                            'NAME' => $arMenuItem[0],
                            'LINK' => $arMenuItem[1],
                        );
                        $arMenuCatalog[$firstParent]['ITEMS'][$secondParent] = $item;
                    }
                    if (!$arMenuItem[3]['IS_PARENT'] && $arMenuItem[3]['DEPTH_LEVEL'] == 2) {
                        $item = array(
                            'NAME' => $arMenuItem[0],
                            'LINK' => $arMenuItem[1],
                        );
                        $arMenuCatalog[$firstParent]['ITEMS'][] = $item;
                    }
                    if (!$arMenuItem[3]['IS_PARENT'] && $arMenuItem[3]['DEPTH_LEVEL'] == 3) {
                        $item = array(
                            'NAME' => $arMenuItem[0],
                            'LINK' => $arMenuItem[1],
                        );
                        $arMenuCatalog[$firstParent]['ITEMS'][$secondParent]['ITEMS'][] = $item;
                    }
                }
            }
        }
        $arResult = array(
            'LINK' => '/catalog/',
            'NAME' => 'Каталог',
        );
        if (!empty($arMenuCatalog)) {
            $arResult['ITEMS'] = $arMenuCatalog;
        }
        return $arResult;
    }
}