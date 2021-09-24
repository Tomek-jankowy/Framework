<?php

use App\Core\Settings\Service\FileManager;

/**
 * Hook schema
 * 
 * Database schema
 */
function hook_schema( &$schema )
{
    $fileManager = new FileManager;
    $modulelist = $fileManager->loadFile('modulelist');

    foreach( array_keys($modulelist) as $name){
        $name .= "_schema";
        if( function_exists($name) ){
            $newSchema = $name();
            if(is_array($newSchema)){
                $name = explode('_', $name)[0];
                $schema[$name] = $newSchema;
            }
        }
    }
}