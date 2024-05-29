<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);
?>
<div class="navbar hidden-md-down">
    <div class="container">
        <ul class="navbar-nav">
            <?if(!empty($arResult['CATALOG_MENU'])):?>
                <li class="menu-item">
                    <a class="nav-link" href="<?=$arResult['CATALOG_MENU']['LINK']?>">
                        <!--<span class="down-arrow">-->
                            <?=$arResult['CATALOG_MENU']['NAME']?>
                        <!--</span>-->
                    </a>
                    <?/*<div class="dropdown-menu dropdown-menu-columns-3">
                        <div class="dropdown-menu-column dropdown-menu-column-width-2">
                            <?foreach($arResult['CATALOG_MENU']["ITEMS"] as $arSubItemKey => $arSubItem):?>
                                <?if($arSubItem["ITEMS"]):?>
                                    <div class="dropdown-sub-menu show" id="catalog-<?=$arSubItemKey;?>-tab">
                                        <div class="dropdown-menu-column">
                                            <?foreach($arSubItem["ITEMS"] as $key => $arSubSubItem):?>
                                                <div class="dropdown-sub-menu-category">
                                                    <a href="<?=$arSubSubItem["LINK"]?>"><?=$arSubSubItem["NAME"]?></a>
                                                    <?if($arSubSubItem["ITEMS"]):?>
                                                        <div class="dropdown-sub-menu-category-body">
                                                            <?foreach($arSubSubItem["ITEMS"] as $arSubSubSubItem):?>
                                                                <a href="<?=$arSubSubSubItem["LINK"]?>"><?=$arSubSubSubItem["NAME"]?></a>
                                                            <?endforeach;?>
                                                        </div>
                                                    <?endif;?>
                                                </div>
                                            <?endforeach;?>
                                        </div>
                                    </div>
                                <?endif;?>
                            <?endforeach;?>
                        </div>
                        <div class="dropdown-menu-column dropdown-menu-column-width-1">
                            <?foreach($arResult['CATALOG_MENU']["ITEMS"] as $arSubItemKey => $arSubItem):?>
                                <a class="left-arrow js-open-blok<?if($arSubItemKey == 0):?> active<?endif;?>" href="<?=$arSubItem["LINK"]?>" data-openblock="catalog-<?=$arSubItemKey;?>-tab"><?=$arSubItem["NAME"]?></a>
                            <?endforeach;?>
                        </div>
                    </div>*/?>
                </li>
            <?endif;?>
            <?if(!empty($arResult['MAIN_MENU'])):?>
                <?foreach($arResult['MAIN_MENU'] as $arMainItem):?>
                    <li class="menu-item">
                        <a class="nav-link" href="<?=$arMainItem['LINK']?>">
                            <?if($arMainItem["ITEMS"] || $arMainItem["THREE_ITEMS"]):?><span class="down-arrow"><?endif;?>
                            <?=$arMainItem['NAME']?>
                            <?if($arMainItem["ITEMS"] || $arMainItem["THREE_ITEMS"]):?></span><?endif;?>
                        </a>
                        <?if($arMainItem["ITEMS"]):?>
                            <div class="dropdown-menu dropdown-menu-columns-3">
                                    <?
                                    $arCols = arrayCol(count($arMainItem["ITEMS"]), 3);
                                    ?>
                                    <?foreach($arCols as $arCol):?>
                                        <div class="dropdown-menu-column">
                                            <?foreach($arCol as $item):?>
                                                <a href="<?=$arMainItem["ITEMS"][$item-1]["LINK"];?>"><?=$arMainItem["ITEMS"][$item-1]["NAME"];?></a>
                                            <?endforeach;?>
                                        </div>
                                    <?endforeach;?>
                            </div>
                        <?elseif ($arMainItem["THREE_ITEMS"]):?>
                            <div class="dropdown-menu dropdown-menu-columns-4">
                                <div class="dropdown-menu-column dropdown-menu-column-width-1">
                                    <?foreach($arMainItem["THREE_ITEMS"] as $arSubItemKey => $arSubItem):?>
                                        <a class="<?if($arSubItem["ITEMS"]):?>right-arrow js-open-blok<?endif;?> <?if($arSubItemKey == 0):?>active<?endif;?>" href="<?=$arSubItem["LINK"]?>" data-openblock="tab-<?=$arSubItemKey?>-<?=$arSubItem["NAME"]?>"><?=$arSubItem["NAME"]?></a>
                                    <?endforeach;?>
                                </div>
                                <div class="dropdown-menu-column dropdown-menu-column-width-3">
                                    <?foreach($arMainItem["THREE_ITEMS"] as $arSubItemKey => $arSubItem):?>
                                        <div class="dropdown-sub-menu<?if($arSubItemKey == 0):?> show<?endif;?>" id="tab-<?=$arSubItemKey?>-<?=$arSubItem["NAME"]?>">
                                            <?
                                            $arCols = arrayCol(count($arSubItem["ITEMS"]), 3);
                                            ?>
                                            <?foreach($arCols as $arCol):?>
                                                <div class="dropdown-menu-column">
                                                    <?foreach($arCol as $item):?>
                                                        <a href="<?=$arSubItem["ITEMS"][$item-1]["LINK"];?>"><?=$arSubItem["ITEMS"][$item-1]["NAME"];?></a>
                                                    <?endforeach;?>
                                                </div>
                                            <?endforeach;?>
                                        </div>
                                    <?endforeach;?>
                                </div>
                            </div>
                        <?endif;?>
                    </li>
                <?endforeach;?>
            <?endif;?>
            <?if(!empty($arResult['TYPE_MENU'])):?>
                <?foreach($arResult['TYPE_MENU'] as $arTypeItem):?>
                    <li class="menu-item icon <?=(isset($arTypeItem[3]["CLASS"]) ? $arTypeItem[3]["CLASS"] : "");?>" style="position: relative;">
                        <a class="nav-link" href="<?=$arTypeItem[1]?>">
                            <?=$arTypeItem[0]?>
                        </a>
                    </li>
                <?endforeach;?>
            <?endif;?>
        </ul>
    </div>
</div>