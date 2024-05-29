<?php
/**
 * handle function to print_r datas
 *
 * @param $data
 * @param bool $sli
 */
function pre($data, $sli = false)
{
    if ($data) {
        echo "<pre>";
        print_r($data);
        echo "</pre>";
    } else {
        var_dump($data);
    }
}

/**
 * функция для получения массив элементов из инфоблока
 *
 * @param $iblockId
 * @param $arSelect
 * @param string $filter
 * @return array
 */
function getFromIB(
    $iblockId,
    $arSelect,
    $filter = "",
    $limit = false,
    $sortBy = Array("sort" => "ASC"),
    $returnCode = ""
) {
    CModule::IncludeModule('iblock');
    $return = array();
    $arFilter = Array();
    $arFilter["IBLOCK_ID"] = IntVal($iblockId);
    $arFilter["ACTIVE"] = "Y";
    if (!empty($filter)) {
        foreach ($filter as $filterKey => $filterValue) {
            $arFilter[$filterKey] = $filterValue;
        }
    }
    $res = CIBlockElement::GetList(
        $sortBy,
        $arFilter,
        false,
        $limit,
        $arSelect
    );
    while ($ob = $res->fetch()) {
        if (!empty($returnCode)) {
            $return[] = $ob[$returnCode];
        } else {
            $return[] = $ob;
        }
    }
    return $return;
}

function upperCaseUrlRedirect(){
    global $APPLICATION;
    $currentDir = $APPLICATION->GetCurDir();
    if(
        preg_match('/[A-Z]/', $currentDir)
    ){
        localredirect(strtolower($currentDir), false, '301 Moved permanently');
    }
}

function detailTextFromProp($ID = false) {
    global $noCheckText;

    CModule::IncludeModule("iblock");
    $arFilter = [
        "IBLOCK_ID" => 26,
        "ACTIVE" => "Y",
        "INCLUDE_SUBSECTIONS" => "Y",
        "!PROPERTY_FILES" => false
    ];
    if ($ID > 0) {
        $arFilter['ID'] = $ID;
    }

    $elems = [];
    $r = CIBlockElement::GetList([], $arFilter, false, false, ['ID', 'NAME', 'PROPERTY_FILES']);
    while ($res = $r->fetch()){
        $elems[] = $res;
    }

    $el = new CIBlockElement;
    foreach ($elems as $k => $item) {
        $text = CFile::GetFileArray($item["PROPERTY_FILES_VALUE"]);
        $file = file_get_contents($_SERVER["DOCUMENT_ROOT"].$text["SRC"]);
        if (strlen($file) == 0) continue;
        $arLoadProductArray = Array(
            "DETAIL_TEXT_TYPE" => "html",
            "DETAIL_TEXT" => html_entity_decode($file),
        );
        $noCheckText = true;
        if ($el->Update($item["ID"], $arLoadProductArray)) {
            CIBlockElement::SetPropertyValuesEx($item["ID"], 26, ['FILES' => ["VALUE" => ["del" => "Y"]]]);
            Bitrix\Iblock\PropertyIndex\Manager::updateElementIndex(26, $item["ID"]);
        }
        $noCheckText = false;
    }
    return 'detailTextFromProp();';
}

function showCabinetLink()
{
	global $USER, $APPLICATION;

	$userID = $USER->GetID();
	$title = 'Мой кабинет';
	$link = '/personal/';
	$html = '<!-- noindex -->';
	if ($userID) {
		$name = $USER->GetFullName();

		$html .= '<a rel="nofollow" title="' . $title . '" class="personal-link dark-color" href="' . $link . '">';
		$html .= '<i class="svg inline svg-inline-cabinet"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="17" viewBox="0 0 14 17"><defs><style>.uscls-1{fill: #222;fill-rule: evenodd;}</style></defs><path class="uscls-1" d="M13.969,16a1,1,0,1,1-2,0H11.927C11.578,14.307,9.518,13,7,13s-4.575,1.3-4.924,3H2.031a1,1,0,0,1-2,0,0.983,0.983,0,0,1,.1-0.424C0.7,12.984,3.54,11,7,11S13.332,13,13.882,15.6a1.023,1.023,0,0,1,.038.158c0.014,0.082.048,0.159,0.058,0.243H13.969ZM7,10a5,5,0,1,1,5-5A5,5,0,0,1,7,10ZM7,2a3,3,0,1,0,3,3A3,3,0,0,0,7,2Z"></path></svg></i>';

		if (!empty($name)) {
			$html .= '<span class="wrap"><span class="name">' . $name . '</span></span>';
		} else {
			$html .= '<span class="wrap"><span class="name">' . $title . '</span></span>';
		}
		$html .= '</a>';
	} else {
		$url = ((isset($_GET['backurl']) && $_GET['backurl']) ? $_GET['backurl'] : $APPLICATION->GetCurUri());
		$html .= '<a rel="nofollow" title="' . $title . '" class="personal-link dark-color animate-load" data-event="jqm" data-param-type="auth" data-param-backurl="' . $url . '" data-name="auth" href="' . $link . '">';
		$html .= '<i class="svg inline svg-inline-cabinet"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="17" viewBox="0 0 16 17"><defs><style>.loccls-1{fill: #222;fill-rule: evenodd;}</style></defs><path class="loccls-1" d="M14,17H2a2,2,0,0,1-2-2V8A2,2,0,0,1,2,6H3V4A4,4,0,0,1,7,0H9a4,4,0,0,1,4,4V6h1a2,2,0,0,1,2,2v7A2,2,0,0,1,14,17ZM11,4A2,2,0,0,0,9,2H7A2,2,0,0,0,5,4V6h6V4Zm3,4H2v7H14V8ZM8,9a1,1,0,0,1,1,1v2a1,1,0,0,1-2,0V10A1,1,0,0,1,8,9Z"></path></svg></i>';
		$html .= '<span class="wrap"><span class="name">Войти</span></span>';
		$html .= '</a>';
	}
	$html .= '<!-- /noindex -->';
	echo $html;
}
function showLogo(){
	global $arSite,$APPLICATION;
	$isIndex = $APPLICATION->GetCurDir() == '/';
	$arTheme = CNext::GetFrontParametrsValues(SITE_ID);

	if($isIndex) $text = '';
	else $text = '<a href="'.SITE_DIR.'">';

	if($arImg = unserialize(\Bitrix\Main\Config\Option::get(CNext::moduleID, "LOGO_IMAGE", serialize(array()))))
		$text .= '<img src="'.CFile::GetPath($arImg[0]).'" alt="'.$arSite["SITE_NAME"].'" title="'.$arSite["SITE_NAME"].'" />';
	elseif(CNext::checkContentFile(SITE_DIR.'/include/logo_svg.php'))
		$text .= File::getFileContents($_SERVER['DOCUMENT_ROOT'].SITE_DIR.'/include/logo_svg.php');
	else
		$text .= '<img src="'.$arTheme["LOGO_IMAGE"].'" alt="'.$arSite["SITE_NAME"].'" title="'.$arSite["SITE_NAME"].'" />';

	if(!$isIndex) $text .= '</a>';

	return $text;
}


function setSeoData()
{
    global $APPLICATION;

    $title = '';
    $descr = '';
    [$uri,$get] = explode('?', $_SERVER['REQUEST_URI']);
//    $uri = $url[0];

    if (strstr($uri, '/dekor-dlya-doma/') {
        $title = 'Купить эксклюзивные подарки для интерьера | Элитные Подарки';
        $descr = 'Приобрести декор для дома премиум-класса 👑 в нашем интернет-магазине. Широкий выбор эксклюзивных предметов интерьера с доставкой по Москве и России — покупайте декоративные элементы для создания уюта в Вашем доме 🏠!';
    }

    if (strstr($uri, '/aksesuary/') {
        $title = 'Аксессуары для кабинета премиум-класса | Элитные Подарки';
        $descr = 'Купить эксклюзивные аксессуары для рабочего стола и кабинета из дорогих и натуральных материалов в интернет-магазине «Элитные подарки» 🎁. Оперативная доставка 🚗 в любую точку России и СНГ!';
    }

    if (strstr($uri, '/suvenirnoe-oruzhie/') {
        $title = 'Купить сувенирное оружие в подарок | Элитные Подарки';
        $descr = 'Приобрести холодное ⚔️ и огнестрельное оружие в подарок 🎁 мужчине в интернет-магазине «Элитные подарки». Широкий выбор подарочного или декоративного оружия, лучшие цены, оперативная доставка — звоните 📞 +7 (495) 797-71-31!';
    }

    if (strstr($uri, '/dlya-piknika/' || strstr($uri, '/dlya-piknika/nabory-dlya-piknika/' ) {
        $title = 'Купить подарочные наборы для пикника | Элитные Подарки';
        $descr = 'Мы предлагаем Вам выбрать набор посуды для пикника в подарочном чемодане или корзинке 🧺 на 4 и 6 персон в каталоге нашего магазина. Качественная упаковка и оперативная доставка 🚗 по России и СНГ.';
    }

    if (strstr($uri, '/elitnaya-posuda/') {
        $title = 'Купить дорогой набор посуды премиум класса | Элитные Подарки';
        $descr = 'Предлагаем приобрести элитную посуду 🍽️ класса люкс по лучшим ценам для Ваших родных, начальников и коллег. Закажите коллекционную фарфоровую посуду на сайте или по телефону 📞 +7 (495) 797-71-31.';
    }

    if (strstr($uri, '/trosti/') {
        $title = 'Трость с клинком купить в Москве | Элитные Подарки';
        $descr = 'Купить красивую трость с секретом🗡️ для ходьбы и самообороны по лучшим ценам в нашем интернет-магазине. Заказать элитную трость ручной работы можно на сайте или по телефонум +7 (495) 797-71-31.';
    }
    if (strstr($uri, '/nozhi/') {
        $title = 'Купить коллекционные ножи в Москве | Элитные Подарки';
        $descr = 'Широкий ассортимент подарочных ножей🗡️ ручной работы от мастеров из Златоуста, Ворсмы и компании Ясный Сокол. Заказать сувенирный набор ножей с можно на нашем сайте или по телефону📞 +7 (495) 764-87-83.';
    }

    if (strstr($uri, '/religioznye/') {
        $title = 'Эксклюзивные и элитные религиозные подарки | Элитные Подарки';
        $descr = 'Приобрести религиозные подарки православной ☦️, мусульманской☪️, иудейской или католической тематики от лучших мастеров России и зарубежья на сайте нашего интернет-магазина. Лучшие цены и оперативная доставка!';
    }

    if (strstr($uri, '/nagradnaya-atributika/') {
        $title = 'Купить наградную атрибутику в Москве | Элитные Подарки';
        $descr = 'Большой выбор памятной атрибутики: медалей 🥇, поясов, кубков 🏆 и других символов победы в каталоге нашего интернет-магазина. Мы гарантируем безупречное качество, доступные цены и оперативную доставку.';
    }

    if (strstr($uri, '/aviatsiya/') {
        $title = 'Подарочные издания книг об авиации | Элитные Подарки';
        $descr = 'Приобрести коллекционные книги с кожаным переплетом в тематике «Авиация» 🛧 Вы можете на сайте интернет-магазина «Элитные Подарки». Профессиональные консультанты ⭐ помогут Вам подобрать отличное издание.';
    }

    if (strstr($uri, '/knigi/biznes/') {
        $title = 'Подарочные книги по бизнесу и психологии | Элитные Подарки';
        $descr = 'Широкий ассортимент элитной подарочной литературы 📚 в тематике «Бизнес 💰 и психология» в каталоге интернет-магазина «Элитные Подарки». Лучшие цены, качественная упаковка и оперативная доставка — звоните +7 (495) 797-71-31!';
    }

    if (strstr($uri, '/knigi/biografiya-memuary') {
        $title = 'Эксклюзивные издания биографий и мемуаров | Элитные Подарки';
        $descr = 'Купить коллекционные книги с биографиями и мемуарами в кожаном переплете с доставкой 🚚 по Москве и всей России на сайте нашего интернет-магазина «Элитные Подарки».';
    }

    if (strstr($uri, '/knigi/istoricheskie-knigi/') {
        $title = 'Купить исторические подарочные книги | Элитные Подарки';
        $descr = 'Приобрести дорогие и эксклюзивные издания книг 📕 по истории в подарок 🎁 в интернет-магазине «Элитные Подарки». Широкий ассортимент подарочной литературы класса премиум с доставкой по России и СНГ.';
    }
    if (strstr($uri, '/knigi/bank-i-finansy/') {
        $title = 'Подарочные книги по экономике и финансам | Элитные Подарки';
        $descr = 'Купить эксклюзивные издания книг 📖 по направлению «Банк 🏦 и финансы💰» в кожаном переплете в нашем интернет-магазине. Закажите vip книги с доставкой по Москве и всей России.';
    }
    if (strstr($uri, '/knigi/klassika/') {
        $title = 'Подарочные издания классической литературы | Элитные Подарки';
        $descr = 'Купить элитные👑 коллекционные издания📚 российских и зарубежных классиков на сайте нашего интернет-магазина. Широкий ассортимент произведений известных авторов в кожаном переплете и  с золотым тиснением.';
    }
    if (strstr($uri, '/knigi/biblioteki/') {
        $title = 'Подарочные коллекционные собрания сочинений | Элитные Подарки';
        $descr = 'Купите элитные 💎 собрания сочинений в кожаном переплете и дорогом 🪙 оформлении в подарок для важного человека. Оформить заказ можно на сайте нашего интернет-магазина или по телефону +7 (495) 797-71-31.';
    }
    if (strstr($uri, '/knigi/kollektsionnye-izdaniya/') {
        $title = 'Купить коллекционные многотомные издания книг | Элитные Подарки';
        $descr = 'Широкий ассортимент эксклюзивных коллекционных многотомников в драгоценном💎 оформлении для домашней библиотеки 📚. Профессиональные консультанты помогут выбрать подарок для самого дорого человека.';
    }
    if (strstr($uri, '/knigi/kulinariya/') {
        $title = 'Эксклюзивные подарочные книги по кулинарии | Элитные Подарки';
        $descr = 'Большой выбор дорогих коллекционных книг для поклонников кулинарии в каталоге нашего интернет-магазина «Элитные Подарки». ⏩Приобрести кулинарные книги для гурманов с доставкой по России и СНГ.';
    }
    if (strstr($uri, '/knigi/geografiya/') {
        $title = 'Купить кожаные подарочные книги по географии | Элитные Подарки';
        $descr = 'Широкий ассортимент элитных книг с переплетами ручной работы про путешествия и для путешественников❤️. Оформить заказ✍ можно на сайте нашего интернет-магазина или по телефону +7 (495) 797-71-31.';
    }
    if (strstr($uri, '/knigi/evrei-iudaizm/') {
        $title = 'Купить подарочные иудейские книги | Элитные Подарки';
        $descr = 'Приобрести эксклюзивную подарочную Тору и другие коллекционные издания еврейской литературы в каталоге нашего интернет-магазина. ⚡Широкий ассортимент подарочных книг в кожаных переплетах ручной работы — звоните +7 (495) 797-71-31!';
    }
    if (strstr($uri, '/knigi/okhota-i-rybalka/') {
        $title = 'Подарочные книги об охоте и оружии | Элитные Подарки';
        $descr = 'Заказать эксклюзивные коллекционные книги 📖 в кожаном переплете для любителей охоты и оружия ⚔️ в нашем интернет-магазине. Широкий ассортимент vip книг с доставкой 🚚 по России и СНГ.';
    }
    if (strstr($uri, '/knigi/ezhednevniki/'  || strstr($uri, '/aksesuary/ezhednevniki/') {
        $title = 'Купить кожаный ежедневник | Элитные Подарки';
        $descr = 'Заказать ежедневник 📔 в кожаном переплете ручной работы по лучшим ценам и доставкой по Москве и всей России Вы можете в нашем интернет-магазине — широкий выбор записных книжек в подарок для мужчин и женщин.';
    }
    if (strstr($uri, '/knigi/zheleznye-dorogi/') {
        $title = 'Подарочные книги про железную дорогу | Элитные Подарки';
        $descr = 'Купить коллекционную книгу про железные дороги 🛤️ в нашем интернет-магазине. ❗Большой ассортимент эксклюзивных изданий в кожаном переплете ручной работы и декорированных полудрагоценными камнями.';
    }
    if (strstr($uri, '/knigi/zhenshchiny/') {
        $title = 'Женский кожаный ежедневник в подарок | Элитные Подарки';
        $descr = 'Купить женский ежедневник в кожаном переплете 📔 ручной работы.в интернет-магазине «Элитные Подарки» с доставкой по России и СНГ. 💯Лучшие цены и профессиональные консультанты — звоните +7 (495) 797-71-31!';
    }
    if (strstr($uri, '/knigi/religioznaya-literatura/') {
        $title = 'Религиозные книги в кожаном переплёте | Элитные Подарки';
        $descr = 'Приобрести эксклюзивные духовные книги📖 в подарочном уникальном исполнении с кожаным переплетом и полудрагоценными камнями можно на сайте нашего интернет-магазина. 💯Лучшие цены и оперативная доставка.';
    }
    if (strstr($uri, '/knigi/iskusstvo/') {
        $title = 'Купить подарочные книги по искусству | Элитные Подарки';
        $descr = ' Приобрести эксклюзивные книги об искусстве 🎨 в оригинальном оформлении ручной работы в интернет-магазине «Элитные подарки».✅ Большой выбор уникальных экземпляров — звоните +7 (495) 797-71-31!';
    }

    if (strstr($uri, '/knigi/islam/') {
        $title = 'Купить подарочные книги по Исламу | Элитные Подарки';
        $descr = 'Приобрести эксклюзивные издания книг по Исламу🕌 в кожаном переплете ручной работы можно в каталоге нашего сайта. Широкий выбор подарочной литературы для мусульман с доставко🚚 по Москве и всей России.';
    }

    if (strstr($uri, '/knigi/entsiklopedii/') {
        $title = 'Купить подарочные энциклопедии | Элитные Подарки';
        $descr = 'Подарочные энциклопедии в дорогих💎 кожаных переплетах ручной работы с полудрагоценными камнями. ✅В нашем каталоге вы быстро найдете эксклюзивный подарок для настоящего ценителя.';
    }

    if (strstr($uri, '/knigi/mvd-spetssluzhby/') {
        $title = 'Купить подарочные книги о спецслужбах | Элитные Подарки';
        $descr = 'Купить подарочные книги📚 об МВД и других спецслужбах в подарочном переплете из натуральной кожи и позолоченным обрезом. ✨Быстрая доставка и лучшие цены в нашем интернет-магазине.';
    }

    if (strstr($uri, '/knigi/meditsina/') {
        $title = 'Подарочные книги о медицине | Элитные Подарки';
        $descr = 'Купить эксклюзивные книги о медицине⚕ в кожаном переплете ручной работы в официальном интернет-магазине «Элитные подарки»💯. Оперативная доставка🚚 по Москве, России и СНГ.';
    }
    if (strstr($uri, '/knigi/more-i-flot/') {
        $title = 'Подарочные книги про море и флот | Элитные Подарки';
        $descr = 'Купить подарочные книги о флоте в кожаном переплете в подарок достойным ценителям моря⛵ и кораблей🚢. Широкий ассортимент коллекционной литературы в каталоге интернет-магазина «Элитные подарки».';
    }

    if (strstr($uri, '/knigi/neft/') {
        $title = 'Купить подарочные книги про нефть | Элитные Подарки';
        $descr = 'Приобрести коллекционные издания книг✨ про нефть и нефтегазовую промышленность каталоге нашего интернет-магазина.💯 Большой выбор эксклюзивных книг в кожаном переплете в окладе из золота или серебра.';
    }

    if (strstr($uri, '/knigi/osobo-tsennye-ekzemplyary/') {
        $title = 'Особо ценные экземпляры подарочных книг | Элитные Подарки';
        $descr = 'Купить особо ценные экземпляры подарочных книг с инкрустацией💎, в футляре или на подставке. Такие книги станут отличным решением для подарка🎁 высоко уважаемому вами человеку.';
    }

    if (strstr($uri, '/knigi/poeziya-proza/') {
        $title = 'Подарочные книги с прозой и поэзией | Элитные Подарки';
        $descr = 'Широкий ассортимент эксклюзивных коллекционных книг с поэзией и прозой в переплете из натуральной кожи и окладе из драгоценных металлов✨. Уникальные и редкие издания в каталоге нашего интернет-магазина.';
    }
    if (strstr($uri, '/knigi/rybalka/') {
        $title = 'Уникальные подарочные книги о рыбалке | Элитные Подарки';
        $descr = 'Коллекционные элитные издания книг о рыбалке в переплете ручной работы из натуральной кожи и полудрагоценных камней. Профессиональные консультанты помогут выбрать идеальный подарок для дорогого человека.';
    }
    if (strstr($uri, '/knigi/semya-detskoe/') {
        $title = 'Подарочные семейные книги и летописи | Элитные Подарки';
        $descr = 'Купить книгу для семейной летописи 👪 в переплете ручной работы из натуральной кожи. ✅ Профессиональные консультанты помогут Вам выбрать дорогой и памятный подарок для семьи и близких.';
    }
    if (strstr($uri, '/knigi/sport/') {
        $title = 'Купить подарочные книги о спорте | Элитные Подарки';
        $descr = 'Предлагаем Вам ознакомиться с ассортиментом коллекционных изданий книг в кожаном переплете  о спорте и великих достижениях🏅 великих спортсменов. 💯Лучшие цены и оперативная доставка — звоните +7 (495) 797-71-31!';
    }

    if (strstr($uri, '/knigi/khristianstvo/') {
        $title = 'Купить подарочные книги про Христианство | Элитные Подарки';
        $descr = 'Широкий ассортимент эксклюзивных книг про Христианство⛪ в кожаном переплете с оригинальным обрамлением в каталоге нашего интернет-магазина. ✅Лучшие цены, качественная упаковка и оперативная доставка.';
    }

    if (strstr($uri, '/dekor-dlya-doma/barometry/') {
        $title = 'Купить настенный барометр для дома | Элитные Подарки';
        $descr = 'Широкий ассортимент✅ подарочных барометров премиального качества в каталоге нашего интернет-магазина —  из дерева, в форме штурвала, с гигрометром и термометром. 📞Звоните +7 (495) 797-71-31!';
    }
    if (strstr($uri, '/dekor-dlya-doma/vazy/') {
        $title = 'Дорогие подарочные вазы из хрусталя и камня | Элитные Подарки';
        $descr = 'Купить декоративные вазы🏺 из хрусталя, нефрита, змеевика и других драгоценных💎 для интерьера. Вазы для цветов из натурального камня станут отличным подарком для самого дорогого человека.';
    }
    if (strstr($uri, '/dekor-dlya-doma/interernye-chasy/') {
        $title = 'Эксклюзивные интерьерные часы | Элитные Подарки';
        $descr = 'Купить эксклюзивные часы⏰ для интерьера в интернет-магазине.⭐Широкий выбор каминных, настенных или настольных часов от мастеров златоустовского часового завода с доставкой по России и СНГ.';
    }
    if (strstr($uri, '/dekor-dlya-doma/kartiny-i-panno/') {
        $title = 'Купить элитные картины и настенные панно | Элитные Подарки';
        $descr = 'Купить панно и картины из янтаря или змеевика в интернет-магазине «Элитные подарки» с доставкой 🚚 по России и СНГ. ✅Широкий ассортимент дорогих и эксклюзивных подарков по лучшим ценам.';
    }
    if (strstr($uri, '/dekor-dlya-doma/kompasy/') {
        $title = 'Купить ювелирный подарочный компас | Элитные Подарки';
        $descr = 'Приобрести дорогой сувенирный компас🧭 в подарок мужчине по лучшим ценам в каталоге нашего интернет-магазина. Оформить заказ можно на сайте или по телефону📞 +7 (495) 797-71-31.';
    }
    if (strstr($uri, '/dekor-dlya-doma/meteostantsii/') {
        $title = 'Настенные метеостанции премиум-класса | Элитные Подарки';
        $descr = 'Большой выбор деревянных метеостанций с часами, барометром, гигрометром и термометром в каталоге официального интернет-магазина «Элитные подарки». Оперативная доставка заказов —  по России и всему миру.';
    }
    if (strstr($uri, '/dekor-dlya-doma/podzornye-truby/') {
        $title = 'Купить подарочную подзорную трубу | Элитные Подарки';
        $descr = 'Если Вы ищете где купить подзорную трубу в подарок🎁 дорогому человеку, то для Вас в каталоге нашего интернет-магазина представлен широкий выбор💯 эксклюзивных моделей по лучшим ценам. Оперативная доставка!';
    }
    if (strstr($uri, '/dekor-dlya-doma/nastolnye-mini-nabory/') {
        $title = 'Настольные мини-наборы для руководителя | Элитные Подарки';
        $descr = 'Приобрести настольный письменный набор класса люкс💎 для кабинета руководителя по лучшим ценам с доставкой по Москве, России и всему миру! ✅Широкий выбор элитных подарков в Нашем интернет-магазине!';
    }
    if (strstr($uri, '/dekor-dlya-doma/fotoramki/') {
        $title = 'Купить дорогие фоторамки | Элитные Подарки';
        $descr = 'Приобрести рамки для фотографий премиум-класса👑 из янтаря в специализированном интернет-магазине «Элитные подарки». Широкий выбор товаров класса люкс с доставкой 🚚 по РФ и всему миру.';
    }
    if (strstr($uri, '/dekor-dlya-doma/shkatulki/') {
        $title = 'Шкатулки и ларцы из натурального камня | Элитные Подарки';
        $descr = 'Широкий ассортимент шкатулок из малахита, янтаря или змеевика с доставкой 🚚 по Москве и всей России. 💯Лучшие цены на каменные шкатулки для украшений в каталоге Нашего интернет-магазина.';
    }
    if (strstr($uri, '/dekor-dlya-doma/podsvechniki-kandelyabry/') {
        $title = 'Коллекционные подсвечники и канделябры | Элитные Подарки';
        $descr = 'Купить подсвечники из камня и канделябры из бронзы по лучшим ценам  в Нашем интернет-магазине ♥️. Широкий выбор эксклюзивных подарков с доставкой🚚 по Москве и всей России — звоните +7 (495) 797-71-31!';
    }
    if (strstr($uri, '/dekor-dlya-doma/stoleshnitsy/') {
        $title = 'Столешницы из янтаря премиум-класса | Элитные Подарки';
        $descr = 'На Нашем сайте Вы можете приобрести столешницы (подносы) из натурального янтаря со вставками из драгоценных металлов⭐. Лучшие цены на товары класса люкс — звоните📞 +7 (495) 797-71-31!';
    }
    if (strstr($uri, '/dekor-dlya-doma/nastolnye-nabory-iz-kamnya/') {
        $title = 'Настольные наборы из камня для руководителей | Элитные Подарки';
        $descr = 'Лучшие письменные настольные наборы⭐ из змеевика, янтаря, мрамора и других натуральных камней с доставкой🚚 по Москве, России и всему миру.  Коллекционные наборы поставляются с документами.';
    }
    if (strstr($uri, '/dekor-dlya-doma/tarelki/') {
        $title = 'Коллекционные декоративные тарелки на стену | Элитные Подарки';
        $descr = 'Купить сувенирные настенные тарелки ручной работы по лучшим ценам🎁. Широкий ассортимент элитных коллекционных моделей⭐ в каталоге нашего интернет-магазина — бесплатная гравировка любой сложности!';
    }
    if (strstr($uri, '/aksesuary/bizhuteriya/') {
        $title = 'Купить бижутерию из натуральных камней | Элитные Подарки';
        $descr = 'Приобрести эксклюзивные ювелирные украшения из натуральных камней💎 с доставкой🚚 по России и СНГ. Наши консультанты помогут выбрать подарок для самого дорогого человека — звоните +7 (495) 797-71-31!';
    }
    if (strstr($uri, '/aksesuary/breloki/') {
        $title = 'Купить эксклюзивные брелоки из бронзы | Элитные Подарки';
        $descr = 'Широкий выбор тематических брелоков из бронзы со вставками из натуральных камней💎. Заказать брелок со знаком зодиака♈ можно на нашем сайте или по телефону +7 (495) 797-71-31 — звоните!';
    }
    if (strstr($uri, '/aksesuary/vizitnitsy/') {
        $title = 'Элитные настольные и карманные визитницы | Элитные Подарки';
        $descr = 'Заказывайте визитницы премиального качества из драгоценных металлов💛 в подарок мужчине с доставкой🚚 по Москве, России и всему миру. Мы организуем красивую и качественную упаковку — звоните +7 (495) 797-71-31!';
    }
    if (strstr($uri, '/aksesuary/zerkaltsa/') {
        $title = 'Купить карманное зеркальце из янтаря | Элитные Подарки';
        $descr = 'Приобрести роскошное дамское зеркальце🪞 ручной работы для девушки, мамы, сестры с доставкой по Москве и всей России. ✔️Наши консультанты помогут Вам с выбором подарка для дорогой женщины.';
    }
    if (strstr($uri, '/aksesuary/pechati-osnastki/') {
        $title = 'Эксклюзивные оснастки для печати | Элитные Подарки';
        $descr = 'Широкий ассортимент премиальных⭐ оснасток для печатей и штампов из драгоценных металлов с декором из натуральных камней. ✔️Красивая упаковка и оперативная доставка в любой уголок России!';
    }
    if (strstr($uri, '/aksesuary/pepelnitsy/') {
        $title = 'Купить пепельницы из натурального камня | Элитные Подарки';
        $descr = 'Приобрести пепельницы из натуральных камней в подарок🎁 мужчине по лучшим ценам и с доставкой по Москве, России и всему миру🌍 — оригинальные сувениры премиум-класса в каталоге нашего интернет-магазина.';
    }
    if (strstr($uri, '/aksesuary/podstavki-dlya-telefona/') {
        $title = 'Подставки для телефонов из натурального камня | Элитные Подарки';
        $descr = 'Приобрести эксклюзивную поставку премиум класса под сотовый телефон📱 на стол в официальном интернет-магазине «Элитные подарки». Оперативная доставка по Москве, РФ и всему миру — обращайтесь в любое время!';
    }
    if (strstr($uri, '/aksesuary/stanki-dlya-britya/') {
        $title = 'Эксклюзивные бритвенные станки | Элитные Подарки';
        $descr = 'Приобрести элитный станок для бритья ручной работы из натуральных камней💯 и драгоценных металлов. ✔️Широкий выбор лучших станков для бритья премиального качества в каталоге нашего интернет-магазина.';
    }
    if (strstr($uri, '/aksesuary/fleshki/') {
        $title = 'Купить эксклюзивные подарочные флешки | Элитные Подарки';
        $descr = 'Большой выбор ювелирных usb-флешек в подарок для мужчин и женщин по лучшим ценам💯. Качественная упаковка и оперативная доставка по Москве, России и СНГ — звоните☎️ +7 (495) 797-71-31!';
    }
    if (strstr($uri, '/aksesuary/sharikovye-ruchki/') {
        $title = 'Элитные подарочные ручки купить в Москве | Элитные Подарки';
        $descr = 'Мы предлагаем купить дорогие ручки🖊️ класса «Люкс»в подарок для руководителя мужчины или женщины по лучшим ценам. Оперативная доставка по России и всему миру — гарантии💯 качества продукции.';
    }
    if (strstr($uri, '/suvenirnoe-oruzhie/bulavy/') {
        $title = 'Купить сувенирную булаву ручной работы | Элитные Подарки';
        $descr = 'Приобрести эксклюзивную подарочную🎁 булаву из ювелирных металлов и редких пород деревьев от мастеров из Златоуста. 💯Быстрая доставка по России и миру, красивая упаковка — звоните +7 (495) 797-71-31!';
    }
    if (strstr($uri, '/suvenirnoe-oruzhie/kinzhaly/') {
        $title = 'Купить подарочный кинжал ручной работы | Элитные Подарки';
        $descr = 'Предлагаем Вам приобрести Златоустовский сувенирный кинжал🗡️ ручной работы из высококлассных металлов, натуральной кожи и редких пород дерева — ⭐элитная упаковка и гарантия высокого качества.';
    }
    if (strstr($uri, '/suvenirnoe-oruzhie/kortiki/') {
        $title = 'Купить сувенирный кортик ручной работы | Элитные Подарки';
        $descr = 'В интернет-магазине «Elit Podarok» широкий выбор эксклюзивных подарочных🎁 кортиков от мастеров из Златоуста — бесплатная гравировка, оперативная доставка🚚 по всем регионам России и миру.';
    }
    if (strstr($uri, '/suvenirnoe-oruzhie/mechi/') {
        $title = 'Купить сувенирные мечи в подарок | Элитные Подарки';
        $descr = 'Приобрести подарочный меч⚔️ из драгоценных металлов и камней, редких пород дерева для любимого и дорогого человека — ❗бесплатная гравировка на рукояти и красивая упаковка.';
    }
    if (strstr($uri, '/suvenirnoe-oruzhie/pistolety/') {
        $title = 'Купить сувенирный пистолет в подарок | Элитные Подарки';
        $descr = 'Широкий ассортимент наградных пистолетов от мастеров Златоустовского оружейного завода в интернет-магазине  «Elit-Podarok». ❗Оружие не боевое и не требует оформления лицензии.';
    }
    if (strstr($uri, '/suvenirnoe-oruzhie/sabli/') {
        $title = 'Купить сувенирную саблю в подарок | Элитные Подарки';
        $descr = 'Большой выбор эксклюзивных и коллекционных сабель ручной работы от Златоустовских мастеров — данное оружие не требует специальной лицензии. Бесплатная гравировка и качественная упаковка.';
    }
    if (strstr($uri, '/suvenirnoe-oruzhie/stilety/') {
        $title = 'Сувенирный стилет ручной работы | Элитные Подарки';
        $descr = 'Приобрести эксклюзивные стилеты от мастеров из Златоуста с доставкой🚚 по России и миру. ❗Коллекционные модели в нашем каталоге не требую оформления лицензии на хранение и перевозку — звоните +7 (495) 797-71-31!';
    }
    if (strstr($uri, '/suvenirnoe-oruzhie/topory/') {
        $title = 'Топор подарочный купить в Москве | Элитные Подарки';
        $descr = 'Широкий ассортимент Златоустовских сувенирных топоров🪓 различного типа: боевые, драчи, колуны, секиры — доставка🚚 по России и всему миру. Наши консультанты ответят на все Ваши вопросы, звоните +7 (495) 797-71-31!';
    }
    if (strstr($uri, '/suvenirnoe-oruzhie/shashki/') {
        $title = 'Купить сувенирную казачью шашку | Элитные Подарки';
        $descr = 'Большой выбор подарочных казачьих шашек ручной работы❗ в подарок коллекционеру или высокопоставленному лицу. Оперативная доставка🚚 по Москве, всем регионам России и миру.';
    }
    if (strstr($uri, '/suvenirnoe-oruzhie/shpagi/') {
        $title = 'Сувенирные шпаги ручной работы | Элитные Подарки';
        $descr = 'Купить коллекционную шпагу оружейной фабрики Златоуст по лучшей цене с доставкой🚚 по всей России и миру в подарок для самого дорогого человека — ❗Ваш подарок придет к заранее установленной дате.';
    }
    if (strstr($uri, '/dlya-piknika/nabory-dlya-shashlyka/') {
        $title = 'Купить подарочный набор для шашлыка | Элитные Подарки';
        $descr = 'Приобрести большой шашлычный набор🔥 в подарочном чемодане, кейсе или ларце в интернет-магазине «Elit-Podarok» с доставкой по Москве, РФ и всему миру — ✔️официальная гарантия, оплата после осмотра.';
    }
    if (strstr($uri, '/dlya-piknika/flyazhki/') {
        $title = 'Элитные подарочные фляжки | Элитные Подарки';
        $descr = 'Широкий ассортимент⭐ элитных фляжек для алкоголя в подарок мужчине. Продукция изготовлена из высококачественных металлов, ценных пород древесины и кожи — Доставка🚚 по России и всему миру.';
    }
    if (strstr($uri, '/elitnaya-posuda/detskaya-posuda/') {
        $title = 'Элитная детская посуда премиум класса | Элитные Подарки';
        $descr = 'Широкий ассортимент дорогой посуды🍽️ ручной работы из драгоценных металлов в каталоге Нашего интернет-магазина. ✅Высокое качество, гарантия от производителя, лучшие цены — звоните и убедитесь +7 (495) 797-71-31!';
    }
    if (strstr($uri, '/elitnaya-posuda/dlya-kofe/') {
        $title = 'Коллекционная посуда для кофе | Элитные Подарки';
        $descr = 'Купить кофейную посуду премиум-класса👑 из императорского фарфора во всевозможных стилях, в том числе под личный заказ в интернет-магазине «Elit-Podarok».🚚 Доставка по Москве, РФ и всему миру.';
    }
    if (strstr($uri, '/elitnaya-posuda/dlya-chaya/') {
        $title = 'Купить премиальную посуду для чая | Элитные Подарки';
        $descr = 'Широкий ассортимент подарочной🎁 чайной посуды от знаменитых фабрик Златоуст и Россия в каталоге интернет-магазине «Elit-Podarok». Оперативная доставка🚚 заказов — в Москве, по РФ и миру.';
    }
    if (strstr($uri, '/elitnaya-posuda/posuda-dlya-napitkov/') {
        $title = 'Эксклюзивная посуда для напитков и коктейлей | Элитные Подарки';
        $descr = 'Большой ассортимент❗ посуды из хрусталя, натуральных камней и драгоценных металлов для подачи и хранения напитков в интернет-магазине «Elit-Podarok». Оперативная доставка🚚 и бесплатная гравировка.';
    }
    if (strstr($uri, '/elitnaya-posuda/posuda-dlya-servirovki/') {
        $title = 'Эксклюзивная посуда для сервировки | Элитные Подарки';
        $descr = ' Широкий ассортимент премиальной👑 посуды для сервировки и украшения праздничного стола. 💯Профессиональные консультанты ответят на все Ваши вопросы и помогут с выбором — пишите и звоните +7 (495) 797-71-31!';
    }
    if (strstr($uri, '/elitnaya-posuda/stolovaya-posuda/') {
        $title = 'Наборы столовой посуды премиум-класса | Элитные Подарки';
        $descr = 'Большой выбор эксклюзивной столовой посуды класса «Люкс»👑: вилки, ножи, блюда и другие изделия из серебра идут в респектабельной упаковке. Доставка🚚 в Москве, по РФ и всему миру!';
    }
    if (strstr($uri, '/nozhi/avtorskie/') {
        $title = 'Авторские ножи ручной работы | Элитные Подарки';
        $descr = 'Широкий ассортимент авторских сувенирных ножей из ценных металлов с доставкой🚚 по Москве, России и миру🌍. Бесплатная гравировка, оплата после получения, гарантия качества!';
    }
    if (strstr($uri, '/nozhi/voennye/') {
        $title = 'Купить военный нож ручной работы | Элитные Подарки';
        $descr = 'Приобрести элитные военные ножи в интернет-магазине «Elit-Podarok» с доставкой по России и миру. Данные изделия не относятся к гражданскому холодному оружию и, что подтверждается сертификатом.';
    }
    if (strstr($uri, '/nozhi/kollektsionnye/') {
        $title = 'Купить коллекционные ножи ручной работы | Элитные Подарки';
        $descr = 'Интернет-магазин «Elit-Podarok» предлагает Вам купить эксклюзивные коллекционные ножи🔪 от ведущих ножевых мастерских. Бесплатная гравировка металлических элементов в подарок🎁.';
    }
    if (strstr($uri, '/nozhi/podarochnye/') {
        $title = 'Купить подарочные ножи ручной работы | Элитные Подарки';
        $descr = 'Широкий ассортимент подарочных ножей от лучших мастеров Златоуста и Ворсмы в каталоге интернет магазина «Elit-Podarok» с доставкой🚚 по всей России и миру. 🗸Бесплатная гравировка в подарок.';
    }
    if (strstr($uri, '/nozhi/skladnye/') {
        $title = 'Складные ножи ручной работы | Элитные Подарки';
        $descr = 'Приобрести дорогие складные ножи от лучших мастеров от Златоустовской оружейной мастерской можно на нашем сайте или по телефону +7 (495) 797-71-31. Товары в наличии, гарантия качества и оперативная доставка.';
    }
    if (strstr($uri, '/nozhi/turisticheskie/') {
        $title = 'Купить туристические ножи ручной работы | Элитные Подарки';
        $descr = 'Широкий ассортимент прочных кованых туристических ножей из дамасской стали с доставкой🚚 по РФ и всему миру.💯 Оплата после получения, гарантия качества, качественная упаковка!';
    }

    if (strstr($uri, '/nozhi/machete-kukri/') {
        $title = 'Купить подарочные мачете и кукри | Элитные Подарки';
        $descr = 'Интернет-магазин «Elit-Podarok» предлагает вам купить сувенирные мачете и курки ручной работы💯 с сертификатами на свободное ношение. 🚚Доставка — в Москве, по России и миру.';
    }
    if (strstr($uri, '/religioznye/musulmanskie/') {
        $title = 'Купить мусульманские религиозные подарки | Элитные Подарки';
        $descr = 'Купить эксклюзивный духовный подарок премиум-класса для мусульманина мужчины или девушки☪️ с быстрой доставкой🚚 в Москве, России и миру.';
    }
    if (strstr($uri, '/religioznye/pravoslavnye/') {
        $title = 'Купить православные религиозные подарки | Элитные Подарки';
        $descr = 'Большой ассортимент эксклюзивных коллекционных вещей для православных☦️ верующих. Премиальное качество изделий из драгоценных металлов, выполненных по церковным канонам. Лучшие цены!';
    }
    if (strstr($uri, '/nagradnaya-atributika/medali/') {
        $title = 'Купить наградные медали под заказ | Элитные Подарки';
        $descr = 'Интернет-магазин «Elit-Podarok» предлагает Вам изготовление памятных и юбилейных медали🏅 с позолотой и гравировкой, инкрустацией драгоценными камнями. 💯Гарантия качества, доступные цены.';
    }
    if (strstr($uri, '/nagradnaya-atributika/poyasa/') !== false) {
        $title = 'Купить наградные пояса под заказ | Элитные Подарки';
        $descr = 'Интернет-магазин «Elit-Podarok» предлагает Вам изготовление наградных поясов для турниров и соревнований. 💯Уникальный дизайн  разрабатываемый индивидуально для заказчика.';
    }

    if (strstr($uri, '/dekor-dlya-doma/suveniry/') !== false) {
        $title = 'Каталог эксклюзивных бизнес подарков | Элитные Подарки';
        $descr = 'Широкий ассортимент vip подарков👑 и дорогих сувениров 🪙 в каталоге нашего интернет-магазина. Купить подарки для любого повода с доставкой 🚗 по Москве и всему СНГ - звоните📞 +7 (495) 797-71-31!';
    }


    if (strstr($uri, '/filter/professii-is-rukovoditelyu/apply/') !== false) {
        $title = 'Купить оригинальный подарок мужчине руководителю на день рождения';
        $descr = 'Широкий ассортимент оригинальных и памятных подарков руководителю мужчине на юбилей от коллектива⭐. Закажите статусный подарок руководителю в интернет-магазине «‎Элитные подарки» 🎁.';
    }

    if (strstr($uri, '/filter/professii-is-direktoru/apply/') !== false) {
        $title = 'Оригинальный подарок директору мужчине на день рождения';
        $descr = 'Большой выбор оригинальных и дорогих подарков 👑 генеральному или коммерческому директору на юбилей от коллектива в интернет-магазине «‎Элитные подарки» 🎁 с доставкой по Москве и другим городам СНГ🚗.';
    }


  if ( ! empty($descr)) {
        $APPLICATION->SetPageProperty("description", $descr);
    }
    if ( ! empty($title)) {
        $APPLICATION->SetPageProperty("title", $title);
    }
}