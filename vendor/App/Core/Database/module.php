<?php

use App\Core\Database\Service\DatabaseSchema;
use App\Core\Settings\Service\FileManager;

function database_install()
{
    $schema = [];
    hook_schema($schema);
    $fileManager = new FileManager;
    $settings = $fileManager->loadFile('settings');
    if(!isset($settings['schema'])){
        $settings['schema'] = [];
    }
    $newSchema = [];
    foreach($schema as $key => $value){
        if( !in_array($key, array_keys($settings['schema'])) ){
            $newSchema[] = $value;
            $settings['schema'][$key] = false;
        }
        else if( in_array($key, array_keys($settings['schema'])) && !$settings['schema'][$key]){
            $newSchema[] = $value;
            $settings['schema'][$key] = false;
        }
    }
    
    unset($schema);
    $fileManager->saveConfig($settings, 'settings');

    if(empty($newSchema)){
        return 1;
    }
    $schemaClass = new DatabaseSchema;
    if(count($newSchema) > 1){
        $schemaClass->multiCreateTable($newSchema);
    }
    else{
        $schemaClass->createTable($newSchema);
    }
}