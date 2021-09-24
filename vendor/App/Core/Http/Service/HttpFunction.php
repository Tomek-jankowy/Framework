<?php

namespace App\Core\Http\Service;

class HttpFunction 
{   
    /**
     * @param string $url
     * @param int $code
     * 
     * @return void
     */
    public function redirect(string $url, int $code) :void
    {
        http_response_code($code);
        header("Location: $url");
    }

    /**
     * @param string $file - file or file path
     */
    public function sendPdf($file, $fileName)
    {
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="'.$fileName.'"');
        readfile("$file");
    }
}