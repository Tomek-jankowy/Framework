<?php

use App\Module\Tomplate\Service\TemplateService;

function module_pre_render_page(&$tempaltePath, &$templateProperty, &$templateName)
{
    #your code ...
}

function module_after_render_template(&$renderArray)
{
    #your code ...
}

function module_theme_property(&$themeProperty)
{
    #your code ...
}


function module_main_block_after_render(&$blockInfo)
{
    #your code ...
}

function module_template_footer()
{
    
    #your code ...
    $template = [
        //'templateName' => [
        //    'values' => [],
        //],
    ];
    
    return \App\Module\Tomplate\Service\TemplateService::staticRender($template);

    #OR

    $templateServie = new TemplateService;
    return $templateServie->render($template);
}

function module_template_menu()
{
    
    #your code ...
    $template = [
        //'templateName' => [
        //    'values' => [],
        //],
    ];
    
    return \App\Module\Tomplate\Service\TemplateService::staticRender($template);

    #OR

    $templateServie = new TemplateService;
    return $templateServie->render($template);
}