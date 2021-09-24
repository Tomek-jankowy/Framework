<?php

namespace App\Core\Settings\Service;

use Exception;
use Symfony\Component\Yaml\Yaml;

define( 'CONFIG_DIR', __DIR__.'/../../../../Config');

class FileManager
{
    protected $fileSystem;
    
    public function __construct()
    {
        $this->fileSystem = $this->loadFileByPath('/FileSystem.yaml');
    }

    /**
     * @param string $fileName - only name file
     * 
     * @return array|false return $file or flase when file no exist
     */
    public function loadFile($fileName)
    {
        $fileProperty = $this->getFilePath($fileName);
        if($fileProperty){
            return $this->loadFileByPath($fileProperty['path']);
        }
       return false;
    }

    /**
     * @param string $filePath - path to file
     * @param bool $configDir 
     * pa
     * @return array|false 
     */
    public function loadFileByPath(string $filePath, $configDir = true) : ?array
    {
        try{
            if($configDir){
                $filePath = CONFIG_DIR.$filePath;
            }
            if( file_exists($filePath) ){
                $File = Yaml::parseFile($filePath);
                return $File;
            }
            else{
                throw new Exception("File $filePath is undefinet");
            }
        }
        catch(Exception $e){
            echo 'Error '.$e;
            return false;
        }
    }

    /**
     * @param string $fileName -file name
     * 
     * @return array|false return file property or false if file does't exist
     */
    protected function getFilePath($fileName) :? array
    {
        if(isset($this->fileSystem)){
            foreach($this->fileSystem as $key => $value){
                if($key == $fileName || $value['name'] == $fileName){
                    return $value;
                }
            }
        }
        return false;
    }

    /**
     * @param array $data 
     * @param array $fileName
     * 
     * @return bool 
     */
    public function saveConfig($data, $fileName )
    {
        $yaml = Yaml::dump($data);
        $fileProperty = $this->getFilePath($fileName);
        if($fileProperty){
            file_put_contents(CONFIG_DIR.$fileProperty['path'], $yaml);
            return true;
        }
        return false;
    }

    public function themeSettings()
    {
        $settingsFile = $this->loadFile('settings');
        if($settingsFile){
            return $settingsFile['theme'];
        }
        return [];
    }
}