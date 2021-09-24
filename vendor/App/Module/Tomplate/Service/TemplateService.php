<?php

namespace App\Module\Tomplate\Service;

use App\Core\Settings\Service\FileManager;

class TemplateService
{
    /**
     * @var App\Core\Settings\Service\FileManager $filrManager 
     */
    protected $fileManager;

    protected $cssArray = [];
    protected $jsArray = [];

    public function __construct()
    {
        $this->fileManager = new FileManager;
        $this->themeSettings = $this->fileManager->themeSettings();
        $this->moduleConfig = $this->fileManager->loadFile('template-list');
        
    }

    /**
     * @param array $template
     */
    public function render($template) :void
    {
        $templateList = $this->fileManager->loadFile('template-list');
        foreach($templateList as $name => $path){
            if(in_array($name, array_keys($template))){
                hook_pre_render_page($path, $template, $name);
                $this->renderPage($path, $template, $name);
                break;
            }
        }
    }

    function renderPage($path, $templateProperty, $name) :void
    {
        
        $themeProperty = $this->themeSettings();

        hook_theme_property($themeProperty);

        $cssArray = array_merge($themeProperty[$this->themeName]['css'],$this->cssArray);
        $jsArray = array_merge($themeProperty[$this->themeName]['js'],$this->jsArray);
        $pageName = $themeProperty[$this->themeName]['hierarchy'][1];
        $pagePath = $this->moduleConfig[$pageName];
        
        
        $pageProperty = [
            $pageName => [
                'values' => [
                    'cssArray' => $cssArray,
                    'jsArray' => $jsArray,
                    'language' => 'pl',
                    'title' => $templateProperty[$name]['title'],
                ],
            ],
        ];

        $this->tomPlate = new TomPlateService();
        $renderArray = $this->tomPlate->renderArray($path, $templateProperty, $name);
        hook_after_render_template($renderArray);
        $blockInfo = [
            'menu' => hook_template_menu(),
            $templateProperty[$name]['blockType'] => $this->tomPlate->renderTemplate($renderArray),
            'footer' => hook_template_footer(),
        ];

        hook_main_block_after_render($blockInfo);

        $page = $this->tomPlate->renderArray($pagePath, $pageProperty, $pageName, $blockInfo);
        print($this->tomPlate->renderTemplate($page));

    }

    protected function themeSettings() :array
    {
        foreach($this->themeSettings as $name => $enable){
            if($enable){
                $this->themeName = $name;
                break;
            }
        }
       
        if(!is_dir("Theme/$name")){
            return [];
        }
        if(!file_exists("Theme/$name/Config/Theme.yaml")){
            return [];
        }
       $themeProperty = $this->fileManager->loadFileByPath("Theme/$name/Config/Theme.yaml", false);
       return $themeProperty;
    }

    public function saveTemplateProperty($name, $path)
    {
        $templateList = [];
        $newTemplateList = [];
        $templateList = $this->fileManager->loadFile('template-list');
        if(!empty($templateList) && is_array($templateList)){
            if(!in_array($name, array_keys($templateList)) 
            || (isset($templateList[$name]) && $templateList[$name] !== $path )){
                $newTemplateList = array_merge($templateList, [$name => $path]);
            }
        }
        else{
            $newTemplateList = [$name => $path];
        }

        if($templateList !== $newTemplateList){
            $newTemplateList = (!empty($templateList))?array_merge($newTemplateList, $templateList) : $newTemplateList;
            $this->fileManager->saveConfig($newTemplateList, 'template-list');
        }
    }

    public function addCss($name, $path){
        $this->cssArray[$name] = $path;
    }

    public function addJs($name, $path){
        $this->jsArray[$name] = $path;
    }

    public static function staticRender($template)
    {
        $templateService = new TemplateService;
        $templateService->render($template);
    }
}