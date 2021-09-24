<?php

declare(strict_types=1);

namespace App\Core\Settings\Service;

use App\Core\Settings\Service\FileManager;

class SettingsService
{
    /**
     * @var App\Core\Settings\Service\FileManager
     */
    private $fileManager;

    public function __construct()
    {
        $this->fileManager = new FileManager;
        $settingsFile = $this->loadSettingsFile('Settings');
    }

    protected function loadSettingsFile($fileName)
    {
       return $this->fileManager->loadFile($fileName);
    }

    public function loadDatabaseConfig($dbName)
    {
        $dbsConfig = $this->fileManager->loadFile('SettingsDB');
        if( !in_array($dbName, array_keys($dbsConfig['Database']))){
            return [];
        }
        foreach($dbsConfig['Database'] as $name => $property){
            if( $name === $dbName && !empty($property) ){
                return $property;
            }
        }
        return [];
    }
}