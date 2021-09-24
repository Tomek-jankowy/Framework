<?php

use App\Core\Settings\Service\FileManager;

function hook_pre_send_curl(&$curl)
{
    $fileManager = new FileManager;
    $modulelist = $fileManager->loadFile('modulelist');

    foreach( array_keys($modulelist) as $name){
        $name .= "_pre_send_curl";
        if( function_exists($name) ){
             $name($curl);
        }
    }
}