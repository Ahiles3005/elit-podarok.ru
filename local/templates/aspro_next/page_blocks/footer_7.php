<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>
<div class="footer_inner <?=($arTheme["SHOW_BG_BLOCK"]["VALUE"] == "Y" ? "fill" : "no_fill");?> compact footer-light">
	<div class="bottom_wrapper">
		<div class="wrapper_inner">
			<div class="row bottom-middle">
				<div class="col-md-3 col-sm-3 copy-block">
					<div class="copy blocks">
						<?$APPLICATION->IncludeFile(SITE_DIR."include/footer/copy/copyright.php", Array(), Array(
								"MODE" => "php",
								"NAME" => "Copyright",
								"TEMPLATE" => "include_area.php",
							)
						);?>
					</div>
					<div class="print-block blocks"><?=CNext::ShowPrintLink();?></div>
					<div id="bx-composite-banner" class="blocks"></div>
					<?$APPLICATION->IncludeFile(SITE_DIR."include/footer/contacts-title.php", array(), array(
							"MODE" => "html",
							"NAME" => "Title",
							"TEMPLATE" => "include_area.php",
						)
					);?>
                    <div class="info">
					<?CNext::ShowHeaderPhones('', true);?>
                        <div class="whatsapp blocks">
							<?$APPLICATION->IncludeFile(SITE_DIR."include/contacts-site-whatsapp.php", Array(), Array("MODE" => "html", "NAME" => "Whatsapp"));?>
                        </div>
					<?CNext::showEmail('email blocks');?>
					<?CNext::showAddress('address blocks');?>
                    </div>
				</div>
				<div class="col-md-6 col-sm-6">
					<?/*<?$APPLICATION->IncludeFile(SITE_DIR."include/footer/contacts-title.php", array(), array(
							"MODE" => "html",
							"NAME" => "Title",
							"TEMPLATE" => "include_area.php",
						)
					);?>
					<div class="row info">
						<div class="col-md-6">
							<?CNext::ShowHeaderPhones('', true);?>
							<?CNext::showEmail('email blocks');?>

						</div>
						<div class="col-md-6">
							<?CNext::showAddress('address blocks');?>
						</div>
					</div>*/?>
					<?/*$APPLICATION->IncludeComponent(
						"bitrix:map.yandex.view",
						"",
						Array(
							"CONTROLS" => array("ZOOM","TYPECONTROL","SCALELINE"),
							"INIT_MAP_TYPE" => "MAP",
							"MAP_DATA" => "a:4:{s:10:\"yandex_lat\";d:55.67580972804118;s:10:\"yandex_lon\";d:37.61293379547903;s:12:\"yandex_scale\";i:17;s:10:\"PLACEMARKS\";a:1:{i:0;a:3:{s:3:\"LON\";d:37.61293379547903;s:3:\"LAT\";d:55.67580972804118;s:4:\"TEXT\";s:182:\"Элитные подарки###RN###Магазин подарков и сувениров, товары для интерьера, товары для отдыха и туризма\";}}}",
							"MAP_HEIGHT" => "300",
							"MAP_ID" => "",
							"MAP_WIDTH" => "100%",
							"OPTIONS" => array("ENABLE_DBLCLICK_ZOOM","ENABLE_DRAGGING")
						)
					);*/?>
                    <div id="index_ya_map">
                        <img src="/images/yamap.jpg" class="img-responsive" loading="lazy">;
                        <script>
                          document.addEventListener("DOMContentLoaded", function(event) {
                            // Задаем элемент для наблюдения
                            let el = document.getElementById('index_ya_map');
                            // Создаем новый observer (наблюдатель)
                            let observer = new IntersectionObserver(function (entries, obs) {
                              entries.forEach(function (entry) {
                                // Если элемент в зоне видимости, то ничего не происходит
                                if (!entry.isIntersecting) return;
                                // Отключаем «наблюдатель»
                                obs.unobserve(entry.target);
                                // Добавляем текст
                                $.ajax({ url: '/include/contacts-site-map-ajax.php',
                                  success: function(data) {
                                    $('#index_ya_map').html(data);
                                  },
                                  error: function(res){console.log('err',res)}
                                });
                              });
                            });
                            // Прикрепляем его к «наблюдателю»
                            observer.observe(el);
                            //setTimeout(function(){},2000)
                          });
                        </script>
                    </div>

				</div>
				<div class="col-md-3 col-sm-3">
					<div class="social-block">
						<?$APPLICATION->IncludeComponent("aspro:social.info.next", "template1", Array(
	"CACHE_TYPE" => "A",	// Тип кеширования
		"CACHE_TIME" => "3600000",	// Время кеширования (сек.)
		"CACHE_GROUPS" => "N",	// Учитывать права доступа
		"COMPONENT_TEMPLATE" => ".default",
		"SOCIAL_TITLE" => GetMessage("SOCIAL_TITLE")
	),
	false
);?>
                        <div class="payment_block">
                            <div class="title">
                                Способы оплаты
                            </div>
                            <span class="mir"></span>
                            <span class="visa"></span>
                            <span class="mastercard"></span>
                        </div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>