<?
global $NavNum;

$NextNavNum = (int)$NavNum + 1;
//именно такой используется в списке товаров поиска
$NavName = 'PAGEN_3';// . $NextNavNum;
global ${$NavName};
${$NavName} = (int)$arResult['VARIABLES']['PAGE_ID'];
include('search.php');
?>