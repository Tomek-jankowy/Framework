<?php

use App\Core\Settings\Service\FileManager;

function appcore_schema()
{
    $fileManager = new FileManager;
    return $fileManager->loadFile('appcore_schema');
}