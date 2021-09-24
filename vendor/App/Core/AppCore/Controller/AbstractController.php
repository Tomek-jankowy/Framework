<?php

namespace App\Core\AppCore\Controller;

use App\Module\Tomplate\Service\TemplateService;
use App\Module\Views\Service\ViewsService;

class AbstractController
{
    public $templateService;

    public function __construct()
    {
        $this->templateService = new TemplateService;
    }

    

}