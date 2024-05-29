<?php
namespace Sdvv\Supermenu;

class Options {
    public function getIblockList() {
        $arResult = array();
        $res = \CIBlock::GetList(
            Array(),
            Array(
                'ACTIVE'=>'Y',
            ), true
        );
        while($ar_res = $res->Fetch())
        {
            $arResult[$ar_res['ID']] = $ar_res['NAME'];
        }
        return $arResult;
    }

    public function getIblockProperties($ID) {
        $arResult = array();
        $properties = \CIBlockProperty::GetList(Array("sort"=>"asc", "name"=>"asc"), Array("ACTIVE"=>"Y", "IBLOCK_ID"=>$ID));
        while ($prop_fields = $properties->GetNext())
        {
            $arResult[$prop_fields['ID']] = $prop_fields['NAME'];
        }
        return $arResult;
    }

    public function getPropertyEnum($ID) {
        $arResult = array();
        $property_enums = \CIBlockPropertyEnum::GetList(Array("DEF"=>"DESC", "SORT"=>"ASC"), Array("PROPERTY_ID" => $ID));
        while($enum_fields = $property_enums->GetNext())
        {
            $arResult[$enum_fields["ID"]] = $enum_fields["VALUE"];
        }
        return $arResult;
    }

    public function getPropertyEnumFull($ID) {
        $arResult = array();
        $property_enums = \CIBlockPropertyEnum::GetList(Array("DEF"=>"DESC", "SORT"=>"ASC"), Array("PROPERTY_ID" => $ID));
        while($enum_fields = $property_enums->GetNext())
        {
            $arResult[$enum_fields["ID"]]['NAME'] = $enum_fields["VALUE"];
            $arResult[$enum_fields["ID"]]['CODE'] = mb_strtolower($enum_fields["XML_ID"]);
            $arResult[$enum_fields["ID"]]['PROPERTY_CODE'] = mb_strtolower($enum_fields["PROPERTY_CODE"]);
        }
        return $arResult;
    }
}