<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
use Sdvv\Supermenu\Options;
CModule::IncludeModule("iblock");?>
<div>Второй уровень</div>
<?$enum = Options::getPropertyEnum($_REQUEST['ID']);?>
<br/>
<select name="SDVV_SUPERMENU[MENU][<?=$_REQUEST['position'];?>][ITEMS][]" multiple="multiple" size="10" style="width: 200px;">
    <?foreach($enum as $enumKey => $enumItem):?>
        <option value="<?=$enumKey;?>"><?=$enumItem;?></option>
    <?endforeach;?>
</select>
<br/><br/>
