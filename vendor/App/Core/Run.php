<?php

use App\Core\Routing\Service\RoutingService;

function run()
{
    
    $jsonPath = __DIR__.'/../../../composer.json';

    if(filesize($jsonPath) == 0){
        return 0;
    }
    $json = fread(fopen($jsonPath, 'r'), filesize($jsonPath));
    $composer = json_decode($json, true);

    if(class_exists('App\Core\Settings\Service\FileManager', true)){
        $fileManager = new App\Core\Settings\Service\FileManager;
    }else{
        include __DIR__."/Settings/Service/FileManager.php";
        $fileManager = new App\Core\Settings\Service\FileManager;
    }

    if($fileManager){
        $fileManager->saveConfig([], 'modulelist');

        $file = $fileManager->loadFile('Modules.info');
        if(!$file){
            return 0;
        }
        $namespaceArray = [];
        $routingFiles = [];
        foreach($file['Core'] as $name => $property){
            
            if($property['enable']){
                array_push($namespaceArray, $property['namespace']);
                addModulToComposer($composer, $property);
                loadHook($name, $property['namespace'], $fileManager);
                if($property['routing']){
                    $routingFile = loadRoutingsFile($property['namespace'], $fileManager );
                    if($routingFile !== false && !empty($routingFile) ) {
                        $routingFiles = array_merge($routingFiles, $routingFile);
                    } 
                }
            }
        }
        foreach($file['Modules'] as $name => $property){
            
            if($property['enable']){
                array_push($namespaceArray, $property['namespace']);
                addModulToComposer($composer, $property);
                loadHook($name, $property['namespace'], $fileManager);
                if($property['routing']){
                    $routingFile = loadRoutingsFile($property['namespace'], $fileManager );
                    if($routingFile !== false && !empty($routingFile) ) {
                        $routingFiles = array_merge($routingFiles, $routingFile); 
                    }
                }
            }
        }

        foreach($composer['autoload']['psr-0'] as $key => $v){
            if( !in_array( $key, $namespaceArray) ){
                unset($composer['autoload']['psr-0'][$key]);
            }
        }
    }
    $newComposer = json_encode($composer, JSON_UNESCAPED_SLASHES );
    if($json !== $newComposer){

        $fp = fopen($jsonPath, 'w');
        fputs($fp, $newComposer);
        fclose($fp);
        exec('cd ../ && composer dump-autoload', $out, $code);
    }

    combineRoutingFile($routingFiles, $fileManager);
}

function addModulToComposer(&$composer, $property)
{
    $namespace = $property['namespace'];
    $psr0 = $composer['autoload']['psr-0'];
    $path = 'vendor/';

    if( !in_array( $namespace, array_keys($psr0) ) && $property['enable'] ){
        $composer['autoload']['psr-0'][$namespace] = $path;
    }
    else if(in_array( $namespace, array_keys($psr0) ) && !$property['enable']){
        unset($composer['autoload']['psr-0'][$namespace]);
    }
    else if(in_array( $namespace, array_keys($psr0)) && $composer['autoload']['psr-0'][$namespace] !== $path){
        $composer['autoload']['psr-0'][$namespace] = $path;
    }
}

function loadRoutingsFile($namespace, $fileManager)
{
    $path = str_replace('\\', '/', $namespace);

    if(file_exists("../vendor/$path"."routing.yaml")){
        $file = $fileManager->loadFileByPath('../vendor/'.$path."routing.yaml", false);
        return $file;
    }

    return false;
}

/**
 * @param array $routingFiles
 */
function combineRoutingFile($routingFiles, $fileManager)
{
    $mainRouting = $fileManager->loadFile('mainrouting');
    if($mainRouting !== $routingFiles){
        $fileManager->saveConfig($routingFiles, 'mainrouting');
    }
}

function loadHook($name, $namespace, $fileManager)
{
    $path = str_replace('\\', '/', $namespace);
    if(file_exists("../vendor/$path"."module.php")){
        include "../vendor/$path"."module.php";
    }
    if(file_exists("../vendor/$path"."hook.php")){
        include "../vendor/$path"."hook.php";
    }
    $name = strtolower($name);
    $moduleListFile = $fileManager->loadFile('modulelist');
    if(empty($moduleListFile) || $moduleListFile === null) $moduleListFile = [];
    if( !in_array($name, array_keys($moduleListFile)) ){
        $moduleListFile = array_merge($moduleListFile, [$name => "../vendor/$path" ]);
        $fileManager->saveConfig($moduleListFile, 'modulelist');
    }
}

function loadRouting()
{
    $routing = new RoutingService;
    $routing->start();
}