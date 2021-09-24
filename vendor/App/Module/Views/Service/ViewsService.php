<?php

namespace App\Module\Views\Service;

use App\Core\Settings\Service\FileManager;
use App\Module\Tomplate\Service\TemplateService;

class ViewsService
{
    /**
     * @var object $filrManager 
     */
    protected $fileManager;

    public function __construct()
    {
        $this->fileManager = new FileManager;
        $this->templateService = new TemplateService;
    }

    public function render($template)
    {
        $templateList = $this->fileManager->loadFile('template-list');
        $this->templateService->addCss('css', '/css/csc/ccs.sss');
        foreach($templateList as $name => $path){
            if(in_array($name, array_keys($template))){
                $this->templateService->renderPage($path, $template, $name);
            }
        }
    }
}