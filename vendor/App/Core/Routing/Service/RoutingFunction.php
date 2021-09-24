<?php

namespace App\Core\Routing\Service;

class RoutingFilesService extends RoutingService
{
    public function __construct()
    {
        parent::__construct();
    }

    public function path($routingName)
    {
        return $this->getPath($routingName);
    }

    public function currentUrl($routingName)
    {
        return $this->getCurentUrl($routingName);
    }

    public function getRoutParam()
    {
        return $this->getCurentParam();
    }
}