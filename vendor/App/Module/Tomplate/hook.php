<?php

use App\Core\Settings\Service\FileManager;

function hook_pre_render_page(&$tempaltePath, &$templateProperty, &$templateName)
{
    $fileManager = new FileManager;
    $modulelist = $fileManager->loadFile('modulelist');
    foreach( array_keys($modulelist) as $name){
        $name .= "_pre_render_page";
        if( function_exists($name) ){
            $name($tempaltePath, $templateProperty, $templateName);
        }
    }
}

function hook_after_render_template(&$renderArray)
{
    $fileManager = new FileManager;
    $modulelist = $fileManager->loadFile('modulelist');
    foreach( array_keys($modulelist) as $name){
        $name .= "_after_render_template";
        if( function_exists($name) ){
            $name($renderArray);
        }
    }
}

function hook_theme_property(&$themeProperty)
{
    $fileManager = new FileManager;
    $modulelist = $fileManager->loadFile('modulelist');
    foreach( array_keys($modulelist) as $name){
        $name .= "_theme_property";
        if( function_exists($name) ){
            $name($themeProperty);
        }
    }
}

function hook_main_block_after_render(&$blockInfo)
{
    $fileManager = new FileManager;
    $modulelist = $fileManager->loadFile('modulelist');
    foreach( array_keys($modulelist) as $name){
        $name .= "_main_block_after_render";
        if( function_exists($name) ){
            $name($blockInfo);
        }
    }
}

function hook_template_footer()
{
    $fileManager = new FileManager;
    $modulelist = $fileManager->loadFile('modulelist');
    foreach( array_keys($modulelist) as $name){
        $name .= "_template_footer";
        if( function_exists($name) ){
            return $name();
        }
    }
}
function hook_template_menu()
{
    $fileManager = new FileManager;
    $modulelist = $fileManager->loadFile('modulelist');
    foreach( array_keys($modulelist) as $name){
        $name .= "_template_menu";
        if( function_exists($name) ){
            return $name();
        }
    }
}