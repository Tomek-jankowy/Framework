<?php

use App\Core\Settings\Service\FileManager;

function hook_run()
{
    $fileManager = new FileManager;
    $modulelist = $fileManager->loadFile('modulelist');
    foreach( array_keys($modulelist) as $name){
        $name .= "_run";
        if( function_exists($name) ){
            $name();
        }
    }
}

function hook_install()
{
    $fileManager = new FileManager;
    $modulelist = $fileManager->loadFile('modulelist');
    foreach( array_keys($modulelist) as $name){
        $name .= "_install";
        if( function_exists($name) ){
            $name();
        }
    }
}

function hook_notfound_pre_render(&$template)
{
    $fileManager = new FileManager;
    $modulelist = $fileManager->loadFile('modulelist');
    foreach( array_keys($modulelist) as $name){
        $name .= "_notfound_pre_render";
        if( function_exists($name) ){
            $name($template);
        }
    }
}

function hook_index_pre_render(&$template)
{
    $fileManager = new FileManager;
    $modulelist = $fileManager->loadFile('modulelist');
    foreach( array_keys($modulelist) as $name){
        $name .= "_notfound_pre_render";
        if( function_exists($name) ){
            $name($template);
        }
    }
}