<?
global $NavNum;
$NextNavNum = (int)$NavNum + 1;
$NavName = 'PAGEN_'.$NextNavNum;
global ${$NavName};
${$NavName} = (int)$arResult['VARIABLES']['PAGE_ID'];
include('filter_section.php');
?>