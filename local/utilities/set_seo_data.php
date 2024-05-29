<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
$csv = array();

if (($file = fopen($_SERVER["DOCUMENT_ROOT"].'/local/products.csv', 'r')) === false)
{
    throw new Exception('There was an error loading the CSV file.');
}
else
{
    while (($line = fgetcsv($file, 1200)) !== false)
    {
        $csv[] = $line;
    }

    fclose($handle);
}

CModule::IncludeModule("iblock");
foreach ($csv as $item){
    $arFilter = array(
        "IBLOCK_ID"=>array(26, 27),
        "PROPERTY_CML2_ARTICLE" => $item[0]
    );
    $r = CIBlockElement::GetList(array(), $arFilter, false, false, array());
    while ($res = $r->GetNextElement()){
        $elem = $res->GetFields();
        $el = new CIBlockElement;
        $arLoadProductArray = Array(
            "IPROPERTY_TEMPLATES"   =>  array(
                "ELEMENT_META_TITLE"                    =>  $item[1],
                "ELEMENT_META_KEYWORDS"                 =>  $item[2],
                "ELEMENT_META_DESCRIPTION"              =>  $item[3],
                "ELEMENT_PAGE_TITLE"                    =>  $item[4],
            )
        );
        $PRODUCT_ID = $elem["ID"];
        $res = $el->Update($PRODUCT_ID, $arLoadProductArray);
        echo "<pre>";
        print_r($elem["ID"]);
        echo "</pre>";
    }
}
?>