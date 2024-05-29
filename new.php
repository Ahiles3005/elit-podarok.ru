<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Новая страница");

LocalRedirect("/");
?>
<?/*
CModule::IncludeModule("iblock");
$section_ids = array(
    2007
);
$arFilter = array(
  "IBLOCK_ID"=>26,
  "ACTIVE"=>"Y",
  "SECTION_ID"=>$section_ids,
  "INCLUDE_SUBSECTIONS"=>"Y"
);
$elem  = array();
$elems  = array();
$r = CIBlockElement::GetList(array(), $arFilter, false, false, array());
while ($res = $r->GetNextElement()){
    $elem = $res->GetFields();
    $elem["PROPS"] = $res->GetProperties();
    $elems[] =$elem;
}

foreach ($elems as $k=>$item){
    $text = CFile::GetFileArray($item["PROPS"]["FILES"]["VALUE"][0]);
    $file = file_get_contents($_SERVER["DOCUMENT_ROOT"].$text["SRC"]);
    if (strlen($file)==0) continue;
    $el = new CIBlockElement;
    $arLoadProductArray = Array(
        "DETAIL_TEXT_TYPE" =>"html",
        "DETAIL_TEXT" => html_entity_decode($file),
    );
    if (!$el->Update($item["ID"], $arLoadProductArray)){
        echo  $el->LAST_ERROR;
    }else{
        echo $item["ID"]. "<br>";
    }
}*/
?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>