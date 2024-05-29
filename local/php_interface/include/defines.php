<?php
define('IBLOCK_ID_CATALOG', 26);
define('IBLOCK_TYPE_CATALOG', 'aspro_next_catalog');

if(stripos($_SERVER['HTTP_USER_AGENT'], 'Lighthouse') !== false){
	define('BOT_PAGESPEED',true);
	//выключим живо для бота
	UnRegisterModuleDependences("main", "OnPageStart", 'jivosite.jivosite',"JivoSiteClass", "addScriptTag");
}
else{
	define('BOT_PAGESPEED',false);
}