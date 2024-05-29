<?php
use Sdvv\Supermenu\Options;
use Bitrix\Main\Page\Asset;
use Bitrix\Main\Config\Option;

CJSCore::Init(array('jquery')) ;

global $APPLICATION;

Asset::getInstance()->addJs("/local/modules/sdvv.supermenu/js/script.js");
$APPLICATION->SetAdditionalCSS('/local/modules/sdvv.supermenu/css/style.css');

$module_id = "sdvv_supermenu";

//Option::set($module_id, 'MENU', '');

/* Сохранение свойств */
//echo '<pre>'; print_r($_REQUEST['SDVV_SUPERMENU']); echo '</pre>';
if (!empty($_REQUEST['save']) && $_REQUEST['save'] == 'Сохранить') {
    if (empty($_REQUEST['SDVV_SUPERMENU']['show_catalog'])) {
        Option::set($module_id, 'show_catalog', '');
    }
    if (!empty($_REQUEST['SDVV_SUPERMENU'])) {
        foreach ($_REQUEST['SDVV_SUPERMENU'] as $superMenuKey => $arSuperMenuData) {
            if (!is_array($arSuperMenuData)) {
                Option::set($module_id, $superMenuKey, $arSuperMenuData);
            } else {
                $serialize = serialize($arSuperMenuData);
                Option::set($module_id, $superMenuKey, $serialize);
            }
        }
    }
}

/* Получение свойств */
$arPlainOptions = array(
    'type_menu',
    'iblock',
    'show_catalog'
);
$arResult = array();
foreach ($arPlainOptions as $arPlainItem) {
    $arResult[$arPlainItem] = Option::get($module_id, $arPlainItem);
}
$arHardOptions = array(
    'MENU'
);
foreach ($arHardOptions as $arHardItem) {
    $arResult[$arHardItem] = unserialize(Option::get($module_id, $arHardItem));
}

//echo '<pre>'; print_r($arResult); echo '</pre>';

//Получим данные из инфоблока
if ($arResult['iblock'] > 0) {
    $arIblockProperties = Options::getIblockProperties($arResult['iblock']);
}

$aTabs = array(
    array("DIV" => "edit1", "TAB" => GetMessage("MAIN_TAB_SET"), "ICON" => "ad_settings", "TITLE" => GetMessage("MAIN_TAB_TITLE_SET")),
);

$tabControl = new CAdminTabControl("tabControl", $aTabs);

$tabControl->Begin();?>
<form method="POST" action="<?echo $APPLICATION->GetCurPage()?>?mid=<?=htmlspecialcharsbx($mid)?>&lang=<?=LANGUAGE_ID?>">
<?$tabControl->BeginNextTab();?>
    <tr class="heading">
        <td colspan="2"><b>Основные параметры</b></td>
    </tr>
    <tr>
        <td width="50%" class="adm-detail-content-cell-l">Тип меню:</td>
        <td width="50%" class="adm-detail-content-cell-r">
            <input type="text" size="30" maxlength="255" value="<?=$arResult['type_menu'];?>" name="SDVV_SUPERMENU[type_menu]" />
        </td>
    </tr>
    <tr>
        <td colspan="2" align="center">
            <div class="adm-info-message-wrap" align="center">
                <div class="adm-info-message">
                    Будет использоваться для вывода пунктов меню перед сгенерированной частью.
                </div>
            </div>
        </td>
    </tr>
    <tr>
        <td width="50%" class="adm-detail-content-cell-l">Вывести пункт "Каталог" в конце меню:</td>
        <td width="50%" class="adm-detail-content-cell-r">
            <input <?if($arResult['show_catalog'] == "Y"):?>checked="checked"<?endif;?> type="checkbox" size="30" maxlength="255" value="Y" name="SDVV_SUPERMENU[show_catalog]" />
        </td>
    </tr>
    <tr>
        <td colspan="2" align="center">
            <div class="adm-info-message-wrap" align="center">
                <div class="adm-info-message">
                    Активация опции добавит в конец меню пункт "Каталог" где будет перечислены все разделы до 3 уровня.
                </div>
            </div>
        </td>
    </tr>
    <tr>
        <td width="50%" class="adm-detail-content-cell-l">Инфоблок для построения основного меню:</td>
        <td width="50%" class="adm-detail-content-cell-r">
            <?
            $arIblockList = Options::GetIblockList();
            ?>
            <select name="SDVV_SUPERMENU[iblock]" class="typeselect js-select-ib">
                <option></option>
                <?foreach ($arIblockList as $iblockKey => $iblockItem):?>
                    <option <?if($iblockKey == $arResult['iblock']):?>selected<?endif;?> value="<?=$iblockKey;?>">[<?=$iblockKey;?>] <?=$iblockItem;?></option>
                <?endforeach;?>
            </select>
        </td>
    </tr>
    <tr>
        <td colspan="2" align="center">
            <div class="adm-info-message-wrap" align="center">
                <div class="adm-info-message">
                    Указывается для того что бы знать откуда брать свойства для построения меню.
                </div>
            </div>
        </td>
    </tr>

    <tr class="heading js-start-hide"<?if($arResult['iblock']):?> style="display: table-row;"<?endif;?>>
        <td colspan="2"><b>Конструктор меню</b></td>
    </tr>

    <?/* Выводим меню */?>
    <?if ($arResult['iblock'] && !empty($arResult['MENU'])):?>
        <?foreach ($arResult['MENU'] as $menuKey => $arMenuItem):?>
            <tr class="js-first-select">
                <td colspan="2" align="center">
                    <select name="SDVV_SUPERMENU[MENU][<?=$menuKey;?>][ID]" class="typeselect">
                        <option></option>
                        <?foreach($arIblockProperties as $propKey => $arProp):?>
                            <option<?if ($propKey == $arMenuItem['ID']):?> selected<?endif;?> value="<?=$propKey;?>"><?=$arProp;?></option>
                        <?endforeach;?>
                    </select>
                    <br/><br/>

                    <?if(empty($arMenuItem['ITEMS']) && empty($arMenuItem['ITEMS_THREE'])):?>
                        <div class="js-control-level">
                            <a data-position="<?=$menuKey;?>" href="#" class="js-add-second-level">Добавить пункт меню второго уровня</a> | <a data-position="<?=$menuKey;?>" class="js-add-second-level-list" href="#">Выбрать несколько пунктов меню для второго уровня</a>
                            <br/><br/>
                        </div>
                    <?endif;?>

                    <div class="js-second-level-block">
                        <?if(!empty($arMenuItem['ITEMS'])):?>
                            <div>Второй уровень</div>
                            <?$enum = Options::getPropertyEnum($arMenuItem['ID']);?>
                            <br/>
                            <select name="SDVV_SUPERMENU[MENU][<?=$menuKey;?>][ITEMS][]" multiple="multiple" size="10" style="width: 200px;">
                                <?foreach($enum as $enumKey => $enumItem):?>
                                    <option <?if(in_array($enumKey, $arMenuItem['ITEMS'])):?> selected="selected"<?endif;?>value="<?=$enumKey;?>"><?=$enumItem;?></option>
                                <?endforeach;?>
                            </select>
                            <br/><br/>
                        <?elseif (!empty($arMenuItem['ITEMS_THREE'])):?>
                            <div>Второй уровень</div>
                            <?$enum = Options::getPropertyEnum($arMenuItem['ID']);?>
                            <br/>
                            <?foreach ($arMenuItem['ITEMS_THREE'] as $threeKey => $arThreeItem):?>
                                <table data-length="position_<?=$threeKey;?>" class="adm-detail-content-table edit-table js-second-level-length">
                                    <tr>
                                        <td width="50%" class="adm-detail-content-cell-l">
                                            <select name="SDVV_SUPERMENU[MENU][<?=$menuKey;?>][ITEMS_THREE][<?=$threeKey;?>][ID]">
                                                <option></option>
                                                <?foreach($enum as $enumKey => $enumItem):?>
                                                    <option <?if($arThreeItem['ID'] == $enumKey):?> selected="selected"<?endif;?>value="<?=$enumKey;?>"><?=$enumItem;?></option>
                                                <?endforeach;?>
                                            </select>
                                        </td>
                                        <td width="50%" class="adm-detail-content-cell-r js-thr">
                                            <?if(empty($arThreeItem['ITEMS'])):?>
                                                <a class="js-add-three-level" href="#">Добавить пункты меню третьего уровня</a>
                                            <?endif;?>
                                            <div class="js-three-level" <?if(!empty($arThreeItem['ITEMS'])):?> style="display: block;" <?endif;?>>
                                                <select name="SDVV_SUPERMENU[MENU][<?=$menuKey;?>][ITEMS_THREE][<?=$threeKey;?>][ITEMS][]" multiple="multiple" size="10" style="width: 200px;">
                                                    <?foreach($enum as $enumKey => $enumItem):?>
                                                        <option <?if(in_array($enumKey, $arThreeItem['ITEMS'])):?> selected="selected"<?endif;?>value="<?=$enumKey;?>"><?=$enumItem;?></option>
                                                    <?endforeach;?>
                                                </select>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            <?endforeach;?>
                        <?endif;?>
                    </div>

                    <a <?if(!empty($arMenuItem['ITEMS_THREE'])):?> style="display: inline;" <?endif;?> class="js-add-second-level js-hide-second-level" data-position="<?=$menuKey;?>" href="#">Добавить пункт меню второго уровня</a>

                    <hr/>
                    <br/>
                </td>
            </tr>
        <?endforeach;?>
    <?endif;?>
    <?/* Выводим меню */?>

    <tr class="js-start-hide"<?if($arResult['iblock']):?> style="display: table-row;"<?endif;?>>
        <td colspan="2" align="center">
            <a class="js-add-first-level" href="#">Добавить пункт меню первого уровня</a>
        </td>
    </tr>

    <tr class="js-start-hide"<?if($arResult['iblock']):?> style="display: table-row;"<?endif;?>>
        <td colspan="2" align="center">
            <div class="adm-info-message-wrap" align="center">
                <div class="adm-info-message" style="text-align: left;">
                    Конструктор пунктов меню на основе свойств.<br/>
                    1. Первым уровнем меню может быть только свойство.<br/>
                    2. Вторым и третьим уровнем меню могут быть только значения этого свойства.<br/>
                    3. Третий уровень вложенности будет доступен только есть нажать "Добавить пункт меню второго уровня". Для списка такая возможность отстутствует.<br/>
                    4. Третий уровень вложенности всегда список.
                </div>
            </div>
        </td>
    </tr>

<?$tabControl->Buttons();?>
    <input class="adm-btn-save" type="submit" name="save" value="Сохранить" />
<?$tabControl->End();?>
</form>