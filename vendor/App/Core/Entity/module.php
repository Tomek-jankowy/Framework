<?php

use App\Core\Settings\Service\FileManager;

function entity_schema()
{
    $fileManager = new FileManager;
    return $fileManager->loadFile('entity_schema');
}