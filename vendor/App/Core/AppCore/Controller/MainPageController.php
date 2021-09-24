<?php

namespace App\Core\AppCore\Controller;

class MainPageController extends AbstractController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $template = [
            'index' => [
                'title' => 'Start',
                'values' => [
                ],
                'blockType' => 'body',
            ],
        ];

        hook_index_pre_render($template);
        
        return $this->templateService->render($template);
    }

    public function test($param)
    {
        dump('witaj na stronie testowej');
        dump($param);
    }

    public function notFound(){
        header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found");
        http_response_code(404);

        $template = [
            'not-found' => [
                'title' => 'Not Fount',
                'values' => [
                    'link' => '/'.$_GET['url'],
                    'cos' => 'Przywitanie: ', 
                ],
                'blockType' => 'body',
            ],
        ];

        hook_notfound_pre_render($template);
        
        return $this->templateService->render($template);
    }
}