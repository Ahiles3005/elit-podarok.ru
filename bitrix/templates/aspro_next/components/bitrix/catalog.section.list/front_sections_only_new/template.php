<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<? $this->setFrameMode( true );?>
<?if($arResult['SECTIONS']):?>
    <div class="sections_wrapper">
        <?if($arParams["TITLE_BLOCK"] || $arParams["TITLE_BLOCK_ALL"]):?>
            <div class="top_block">
                <h3 class="title_block"><?=$arParams["TITLE_BLOCK"];?></h3>
                <a href="<?=SITE_DIR.$arParams["ALL_URL"];?>"><?=$arParams["TITLE_BLOCK_ALL"] ;?></a>
            </div>
        <?endif;?>
        <div class="list items">
            <div class="row margin0 flexbox">
                <?foreach($arResult['SECTIONS'] as $arSection):
                    $this->AddEditAction($arSection['ID'], $arSection['EDIT_LINK'], CIBlock::GetArrayByID($arSection["IBLOCK_ID"], "SECTION_EDIT"));
                    $this->AddDeleteAction($arSection['ID'], $arSection['DELETE_LINK'], CIBlock::GetArrayByID($arSection["IBLOCK_ID"], "SECTION_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_SECTION_DELETE_CONFIRM')));?>
                    <span class="callback-block animate-load twosmallfont colored  white btn-default btn custom-btn" id="<?=$this->GetEditAreaId($arSection['ID']);?>">
                        <a href="<?=$arSection['SECTION_PAGE_URL'];?>"><?=$arSection['NAME'];?></a>
                    </span>
                <?endforeach;?>
            </div>
        </div>
    </div>
<?endif;?>