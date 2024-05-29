<?
use \Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
    "NAME" => "Супер-меню",
    "DESCRIPTION" => "Компонент выводит супер-меню",
    "SORT" => 1,
    "CACHE_PATH" => "Y",
    "PATH" => array(
        "ID" => "SDVV",
        "NAME" => 'Компоненты Студия "DENISOV"',
        "CHILD" => array(
            "ID" => "supermenu",
            "NAME" => "SDVV супер-меню",
        ),
    ),
    "COMPLEX" => "N",
);