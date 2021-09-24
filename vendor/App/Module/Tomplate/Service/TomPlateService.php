<?php

namespace App\Module\Tomplate\Service;

class TomPlateService
{
    public function __construct($cssArray = [], $jsArray = [])
    {
        $this->cssArray = $cssArray;
        $this->jsArray = $jsArray;
    }

    public function renderArray(string $path, array $templateProperty, string $name, array $blockInfo = [])
    {
        $this->renderArray = $this->getTemplateFile((string)$path);
        $this->renderFor($this->renderArray, $templateProperty, $name);
        $this->renderVarable($this->renderArray, $templateProperty, $name);

        if(!empty($blockInfo)){
            $this->renderBlock($this->renderArray, $blockInfo);
        }

        return $this->renderArray;
    }

    public function getTemplateFile($path)
    {
        if(file_exists($path)){
            return file($path);
        }
        exit('File not exist');
    }

    protected function renderFor(&$renderArray, $templateProperty, $templateName)
    {
        foreach($renderArray as $line){
            if(strpos($line, '{{ for')){
                $this->startFor($renderArray, $templateProperty, $templateName);
            }
        }
    }
    protected function startFor(&$renderArray, $templateProperty, $templateName)
    {
        foreach($renderArray as $index => $line){
            if(strpos($line, '{{ for')){
                $foreachProperty = explode('{{ for ', $line);
                    $foreachItems = explode(' as ', $foreachProperty[1]);
                    $iterable = $foreachItems[0];
                        $varable = explode(' => ',$foreachItems[1]);
                    $startIndex = $index;
            }
            if(strpos($line, '{{ endfor }}')){
                $endIndex = $index;
                break;
            }
        }

        if(count($varable) == 2){
            $newline = $this->forWithKey($startIndex, $endIndex, $renderArray, $varable[0], explode(' }}',$varable[1])[0], $templateProperty[$templateName]['values'][$iterable] );
        }
        else{
            $newline = $this->forWithoutKey($startIndex, $endIndex, $renderArray, explode(' }}',$varable[0])[0], $templateProperty[$templateName]['values'][$iterable] );
        }

        foreach($renderArray as $index => $line){
            if($index === $startIndex){
                    $newrenderArray[] = $newline;
            }else{
                if($index < $startIndex || $index > $endIndex){
                    $newrenderArray[] = $line;
                }
            }
        }

        $renderArray = $newrenderArray;
    }
    public function forWithKey($startIndex, $endIndex, $renderArray, $key, $value, $iterable)
    {
        $newline ='';
        foreach($iterable as $k => $v){
            foreach($renderArray as $index => $line){
                if($index > $startIndex && $index < $endIndex){
                    $newline .= str_replace(["{[ $key ]}", "{[ $value ]}"], [$k, $v], $line);
                }
            }
        }
        return $newline;
    }

    public function forWithoutKey($startIndex, $endIndex, $renderArray, $value, $iterable)
    {
        $newline ='';
        foreach($iterable as $v){
            foreach($renderArray as $index => $line){
                if($index > $startIndex && $index < $endIndex){
                    $newline .= str_replace("{[ $value ]}", $v, $line);
                }
            }
        }
    }

    protected function renderVarable(&$renderArray, $templateProperty, $name)
    {
        foreach($templateProperty[$name]['values'] as $key => $value){
            if(!is_array($value)){
                $keys[] = "{[ $key ]}";
                $values[] = $value;
            }
        }
        if(!empty($keys) && !empty($values)){
            foreach($renderArray as $line){
                $newRenderArray[] = str_replace( $keys, $values, $line);
            }
            $renderArray = $newRenderArray;
        }
    }

    protected function renderBlock(&$renderArray, $blockInfo)
    {
        foreach($blockInfo as $key => $v){
            $keys[] = "{% BLOCK $key %}";
        }
        $values = array_values($blockInfo);
        foreach($renderArray as $line){
            $newRenderArray[] = str_replace( $keys, $values, $line);
        }

        $renderArray = $newRenderArray;
    }

    public function renderTemplate($renderArray)
    {
        $templateContent = '';
        foreach($renderArray as $line){
            if(!is_array($line) || !is_object($line)){
                $templateContent .= $line;
            }
            else{
                foreach($line as $miniLine){
                    $templateContent .= $miniLine;
                }
            }
        }
        return $templateContent;
    }
}   