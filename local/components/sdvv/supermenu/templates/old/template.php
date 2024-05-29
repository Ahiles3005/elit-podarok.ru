<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);
?>
<div class="table-menu">
    <table>
        <tr>
            <?if(!empty($arResult['TYPE_MENU'])):?>
                <?foreach($arResult['TYPE_MENU'] as $arTypeItem):?>
                    <td class="menu-item unvisible <?=(isset($arTypeItem[3]["CLASS"]) ? $arTypeItem[3]["CLASS"] : "");?>">
                        <div class="wrap">
                            <a href="<?=$arTypeItem[1]?>">
                                <div>
                                    <?=$arTypeItem[0]?>
                                    <div class="line-wrapper"><span class="line"></span></div>
                                </div>
                            </a>
                        </div>
                    </td>
                <?endforeach;?>
            <?endif;?>
            <?if(!empty($arResult['MAIN_MENU'])):?>
                <?foreach($arResult['MAIN_MENU'] as $arMainItem):?>
                    <td class="menu-item unvisible <?=($arMainItem["ITEMS"] || $arMainItem["THREE_ITEMS"] ? "dropdown" : "")?>">
                        <div class="wrap">
                            <a class="<?=($arMainItem["ITEMS"] || $arMainItem["THREE_ITEMS"] ? "dropdown-toggle" : "")?>" href="<?=$arMainItem['LINK']?>">
                                <div>
                                    <?=$arMainItem['NAME']?>
                                    <div class="line-wrapper"><span class="line"></span></div>
                                </div>
                            </a>
                            <?if($arMainItem["ITEMS"]):?>
                                <span class="tail"></span>
                                <ul class="dropdown-menu">
                                    <?foreach($arMainItem["ITEMS"] as $arSubItem):?>
                                        <li>
                                            <a href="<?=$arSubItem["LINK"]?>" title="<?=$arSubItem["NAME"]?>">
                                                <span class="name"><?=$arSubItem["NAME"]?></span>
                                            </a>
                                        </li>
                                    <?endforeach;?>
                                </ul>
                            <?elseif ($arMainItem["THREE_ITEMS"]):?>
                                <span class="tail"></span>
                                <ul class="dropdown-menu">
                                    <?foreach($arMainItem["THREE_ITEMS"] as $arSubItem):?>
                                        <li class="<?=($arSubItem["ITEMS"] ? "dropdown-submenu" : "")?>">
                                            <a href="<?=$arSubItem["LINK"]?>" title="<?=$arSubItem["NAME"]?>">
                                                <span class="name"><?=$arSubItem["NAME"]?></span>
                                                <?=($arSubItem["ITEMS"] ? '<span class="arrow"><i></i></span>' : '')?>
                                            </a>
                                            <?if($arSubItem["ITEMS"]):?>
                                                <ul class="dropdown-menu toggle_menu">
                                                    <?foreach($arSubItem["ITEMS"] as $key => $arSubSubItem):?>
                                                        <li class="menu-item">
                                                            <a href="<?=$arSubSubItem["LINK"]?>" title="<?=$arSubSubItem["NAME"]?>">
                                                                <span class="name"><?=$arSubSubItem["NAME"]?></span>
                                                            </a>
                                                        </li>
                                                    <?endforeach;?>
                                                </ul>
                                            <?endif;?>
                                        </li>
                                    <?endforeach;?>
                                </ul>
                            <?endif;?>
                        </div>
                    </td>
                <?endforeach;?>
            <?endif;?>
            <?if(!empty($arResult['CATALOG_MENU'])):?>
                <td class="menu-item unvisible <?=($arResult['CATALOG_MENU']["ITEMS"]? "dropdown" : "")?>">
                    <div class="wrap">
                        <a class="<?=($arResult['CATALOG_MENU']["ITEMS"] ? "dropdown-toggle" : "")?>" href="<?=$arResult['CATALOG_MENU']['LINK']?>">
                            <div>
                                <?=$arResult['CATALOG_MENU']['NAME']?>
                                <div class="line-wrapper"><span class="line"></span></div>
                            </div>
                        </a>
                        <?if($arResult['CATALOG_MENU']["ITEMS"]):?>
                            <span class="tail"></span>
                            <ul class="dropdown-menu">
                                <?foreach($arResult['CATALOG_MENU']["ITEMS"] as $arSubItem):?>
                                    <li class="<?=($arSubItem["ITEMS"] ? "dropdown-submenu" : "")?>">
                                        <a href="<?=$arSubItem["LINK"]?>" title="<?=$arSubItem["NAME"]?>">
                                            <span class="name"><?=$arSubItem["NAME"]?></span>
                                            <?=($arSubItem["ITEMS"] ? '<span class="arrow"><i></i></span>' : '')?>
                                        </a>
                                        <?if($arSubItem["ITEMS"]):?>
                                            <ul class="dropdown-menu toggle_menu">
                                                <?foreach($arSubItem["ITEMS"] as $key => $arSubSubItem):?>
                                                    <li class="menu-item <?=($arSubSubItem["ITEMS"] ? "dropdown-submenu" : "")?>">
                                                        <a href="<?=$arSubSubItem["LINK"]?>" title="<?=$arSubSubItem["NAME"]?>">
                                                            <span class="name"><?=$arSubSubItem["NAME"]?></span>
                                                        </a>
                                                        <?if($arSubSubItem["ITEMS"]):?>
                                                            <ul class="dropdown-menu">
                                                                <?foreach($arSubSubItem["ITEMS"] as $arSubSubSubItem):?>
                                                                    <li class="menu-item">
                                                                        <a href="<?=$arSubSubSubItem["LINK"]?>" title="<?=$arSubSubSubItem["NAME"]?>"><span class="name"><?=$arSubSubSubItem["NAME"]?></span></a>
                                                                    </li>
                                                                <?endforeach;?>
                                                            </ul>

                                                        <?endif;?>
                                                    </li>
                                                <?endforeach;?>
                                            </ul>
                                        <?endif;?>
                                    </li>
                                <?endforeach;?>
                            </ul>
                        <?endif;?>
                    </div>
                </td>
            <?endif;?>
        </tr>
    </table>
</div>