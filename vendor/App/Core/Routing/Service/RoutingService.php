<?php

namespace App\Core\Routing\Service;

use App\Core\Settings\Service\FileManager;

class RoutingService
{
    /**
     * @var FileManager $fileManager 
     */
    protected $fileManager;

    public function __construct()
    {
        $this->fileManager = new FileManager;
    }

    public function start()
    {
        $url = $this->getCurentUrl();
        $routingName = $this->getRoutingNameByUrl($url);
        $this->loadFunction($routingName);
    }

    /**
     * @param string $routingName
     * 
     * @return void|false
     */
    private function loadFunction($routingName)
    {
        $property = $this->getRoutingProperty($routingName);
        
        if(empty($property)){
            return 0;
        }

        $className = $property['namespace'];
        $class = new $className;
        $function = $property['function'];
        
        if(isset($this->param) && !empty($this->param)){
            $class->$function($this->param);
        }
        else $class->$function();
    }

    /**
     * @param string $url
     * 
     * @return string routing name
     */
    protected function getRoutingNameByUrl($url)
    {
        $routingFile = $this->getRoutingFile();
        foreach($routingFile as $name => $property){
            if(substr($property['path'], -1) === '%'){
                $explod = explode(str_replace('%', '', $property['path']), $url);
                if($explod 
                && count($explod) == 2 
                && $url === str_replace('%', $explod[1], $property['path']) ){
                    $this->param = explode('/',$explod[1]);
                    return $name;
                }
            }
            if($property['path'] === $url){
                return $name;
            }

        }
        return 'not.found';
    }

    /**
     * @param string $routingName;
     * 
     * @return string path
     */
    protected function getPath($routingName)
    {
        $routingFile = $this->getRoutingFile();
        foreach($routingFile as $name => $property){
            if($name === $routingName){
                return $property['path'];
            }
        }
        return '';
    }

    /**
     * @param string $routingName
     * 
     * @return array property
     */
    protected function getRoutingProperty($routingName)
    {
        $routingFile = $this->getRoutingFile();
        foreach($routingFile as $name => $property){
            if($name === $routingName){
                return $property;
            }
        }
        return [];
    }

    private function setRoutingFile()
    {
        $this->routingFile = $this->fileManager->loadFile('MainRouting');
    }

    public function getRoutingFile()
    {
        if(!isset($this->routingFile) || empty($this->routingFile)){
            $this->setRoutingFile();
        }
        return $this->routingFile;
    }

    private function setUrl()
    {
        if(isset($_GET['url']) && !empty($_GET['url'])){
            $url = ($_GET['url'] === 'index.php' )? '/' : '/'.$_GET['url'];
            $this->url = $url;
        }
    }
    
    protected function getCurentUrl()
    {
        if(!isset($this->url) || empty($this->url)){
            $this->setUrl();
        }
        return $this->url;
    }

    protected function getCurentParam()
    {
        $this->getRoutingNameByUrl($this->getCurentUrl());
        if(isset($this->param) && !empty($this->param)){
            return $this->param;
        }
        return [];
    }
}