<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
use Sdvv\Supermenu\Options;
CModule::IncludeModule("iblock");?>
<?if($_REQUEST['positionSecond'] == 0):?>
    <div>Второй уровень</div>
<?endif;?>
<?$enum = Options::getPropertyEnum($_REQUEST['ID']);?>
<br/>
<table data-length="position_<?=$_REQUEST['position'];?>" class="adm-detail-content-table edit-table js-second-level-length">
    <tr>
        <td width="50%" class="adm-detail-content-cell-l">
            <select name="SDVV_SUPERMENU[MENU][<?=$_REQUEST['position'];?>][ITEMS_THREE][<?=$_REQUEST['positionSecond'];?>][ID]">
                <option></option>
                <?foreach($enum as $enumKey => $enumItem):?>
                    <option value="<?=$enumKey;?>"><?=$enumItem;?></option>
                <?endforeach;?>
            </select>
        </td>
        <td width="50%" class="adm-detail-content-cell-r js-thr">
            <a class="js-add-three-level" href="#">Добавить пункты меню третьего уровня</a>
            <div class="js-three-level">
                <select name="SDVV_SUPERMENU[MENU][<?=$_REQUEST['position'];?>][ITEMS_THREE][<?=$_REQUEST['positionSecond'];?>][ITEMS][]" multiple="multiple" size="10" style="width: 200px;">
                    <?foreach($enum as $enumKey => $enumItem):?>
                        <option value="<?=$enumKey;?>"><?=$enumItem;?></option>
                    <?endforeach;?>
                </select>
            </div>
        </td>
    </tr>
</table>
