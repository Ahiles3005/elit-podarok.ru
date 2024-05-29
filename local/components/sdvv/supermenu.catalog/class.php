<?php
class CCatalogSuperMenu extends CBitrixComponent {

    public $module_id = 'sdvv_supermenu';

    protected $defaultUrlTemplates404 = array();
    protected $componentVariables = array();
    protected $page = '';
    protected $b404;

    protected function setSefDefaultParams()
    {
        $smartBase = ($this->arParams["SEF_URL_TEMPLATES"]["section"]? $this->arParams["SEF_URL_TEMPLATES"]["section"]: "#SECTION_ID#/");

        $this->defaultUrlTemplates404 = array(
            "sections" => "",
            "section" => "#SECTION_ID#/",
            "element" => "#SECTION_ID#/#ELEMENT_ID#/",
            "compare" => "compare.php?action=COMPARE",
            "smart_filter" => $smartBase."filter/#SMART_FILTER_PATH#/apply/",
        );
        $this->componentVariables = array('ELEMENT_ID', 'ELEMENT_CODE', 'SECTION_ID', 'SECTION_CODE');
    }

    protected function getResult()
    {
        $urlTemplates = array();
        if ($this->arParams['SEF_MODE'] == 'Y')
        {
            $variables = array();
            $urlTemplates = \CComponentEngine::MakeComponentUrlTemplates(
                $this->defaultUrlTemplates404,
                $this->arParams['SEF_URL_TEMPLATES']
            );
            $variableAliases = \CComponentEngine::MakeComponentVariableAliases(
                $this->defaultUrlTemplates404,
                $this->arParams['VARIABLE_ALIASES']
            );
            $engine = new CComponentEngine($this);
            if (CModule::IncludeModule('iblock'))
            {
                $engine->addGreedyPart("#SECTION_CODE_PATH#");
                $engine->addGreedyPart("#SMART_FILTER_PATH#");
                $engine->setResolveCallback(array("CIBlockFindTools", "resolveComponentEngine"));
            }
            $this->page = $engine->guessComponentPath(
                $this->arParams['SEF_FOLDER'],
                $urlTemplates,
                $variables
            );

            $this->b404 = false;
            if(!$this->page)
            {
                $this->page = "sections";
                $this->b404 = true;
            }

            if (strlen($this->page) <= 0)
                $this->page = 'index';

            \CComponentEngine::InitComponentVariables(
                $this->page,
                $this->componentVariables, $variableAliases,
                $variables
            );
        }
        else
        {
            $this->page = 'index';
        }

        $this->arResult = array(
            'FOLDER' => $this->arParams['SEF_FOLDER'],
            'URL_TEMPLATES' => $urlTemplates,
            'VARIABLES' => $variables,
            'ALIASES' => $variableAliases
        );
    }

    public function executeComponent()
    {
        $this->setSefDefaultParams();
        $this->getResult();
        $this->arResult['smartFilterPath'] = $this->smartFilterPath();
        $this->includeComponentTemplate($this->page);

        //return $this->arResult;
    }

    public function smartFilterPath() {
        $array = explode('/',$_SERVER['REQUEST_URI']);

        $filterKey = array_search('filter', $array);
        $applyKey = array_search('apply', $array);

        if($filterKey){
            $str = '';
            for($i=$filterKey+1; $i<$applyKey; $i++){
                $str .= $array[$i].'/';
            }

            $str = rtrim($str,'/');

            return $str;
        }
        else{
            return false;
        }
    }
}