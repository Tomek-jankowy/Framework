<?php

use App\Core\Settings\Service\FileManager;
use App\Module\Tomplate\Service\TemplateService;

function tomplate_run()
{
    $fileManager = new FileManager;
    $templateService = new TemplateService;
    $moduleList = $fileManager->loadFile('module.list');
    $themeSettings = $fileManager->themeSettings();

    foreach($moduleList as $name => $path){
        if(is_dir($path.'/template')){
            exec("ls $path/template",$output);
            if(!empty($output)){
                foreach($output as $template){
                    $explode = explode('.', $template);
                    if($explode && count($explode) == 3 && $explode[1] === 'tomp' && $explode[2] === 'html'){
                        $templateService->saveTemplateProperty($explode[0], "$path"."template/$template");
                    }
                }
            }
            
        }
    }

    foreach($themeSettings as $name => $enable){
        if($enable){
            $themeName = $name;
            break;
        }
    }
    $path = "Theme/$themeName/templates";
    $output = null;
    if(is_dir($path)){
        exec("ls $path",$output);
        if(!empty($output)){
            foreach($output as $template){
                $explode = explode('.', $template);
                if($explode && count($explode) == 3 && $explode[1] === 'tomp' && $explode[2] === 'html'){
                    $templateService->saveTemplateProperty($explode[0], "$path/$template");
                }
            }
        }
    }
}