<?php

namespace App\Core\Http\Service;

class JsonResponse
{
    public function __construct(array $data)
    {
        header('Content-Type: application/json');
        print(json_encode($data));
    }
}