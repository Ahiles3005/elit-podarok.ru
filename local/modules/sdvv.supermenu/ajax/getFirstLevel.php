<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule("iblock");
$arResult = array();
if ($_REQUEST['iblock'] > 0) {
    //TODO: В модуле есть метод
    $properties = CIBlockProperty::GetList(Array("sort"=>"asc", "name"=>"asc"), Array("ACTIVE"=>"Y", "IBLOCK_ID"=>$_REQUEST['iblock']));
    while ($prop_fields = $properties->GetNext())
    {
        $arResult[$prop_fields['ID']] = $prop_fields['NAME'];
    }
}
?>
<tr class="js-first-select">
    <td colspan="2" align="center">
        <select name="SDVV_SUPERMENU[MENU][<?=$_REQUEST['position'];?>][ID]" class="typeselect">
            <option></option>
            <?foreach($arResult as $key => $arItem):?>
                <option value="<?=$key;?>"><?=$arItem;?></option>
            <?endforeach;?>
        </select>
        <br/><br/>
        <div class="js-control-level">
            <a href="#" data-position="<?=$_REQUEST['position'];?>" class="js-add-second-level">Добавить пункт меню второго уровня</a> | <a data-position="<?=$_REQUEST['position'];?>" class="js-add-second-level-list" href="#">Выбрать несколько пунктов меню для второго уровня</a>
            <br/><br/>
        </div>
        <div class="js-second-level-block"></div>
        <hr/>
        <br/>
    </td>
</tr>
