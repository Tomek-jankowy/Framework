<?php

namespace App\Core\Database\Service;

class DatabaseSchema
{
    protected $schema;
    protected $db;

    public function __construct()
    {
        $this->db = new DatabaseService;
    }

    /**
     * @param array $schema
     * 
     * @return bool
     */
    public function checkSchema($schema)
    {
        $flag = true;
        $kyes = array_keys($schema);
        foreach($kyes as $tableName){
            if(!$this->db->tableExist($tableName)){
                foreach($schema[$tableName] as $key => $value){
                    if($key !== 'primary'){
                        
                        if(!isset($value['type'])){
                            $flag = false;
                            break;
                        }
                        if(strtoupper($value['type']) === 'VARCHAR' && !isset($value['lenght']) ){
                            $flag = false;
                            break;
                        }
                        $value['type'] = strtoupper($value['type']);
                        if( !isset($value['not null']) || !is_bool($value['not null']) ){
                            $flag = false;
                            break;
                        }
                    }
                }
                if( !isset($schema[$tableName]['primary']) || !in_array($schema[$tableName]['primary'], array_keys($schema[$tableName])) ){
                    $flag = false;
                }
            }
            else $flag = false;
        }
        return $flag;
    }

    /**
     * @return bool
     */
    public function multiCreateTable($schema)
    {
        foreach($schema as $tableProperty){
            if($this->checkSchema($tableProperty) ){
               
                $this->createTable($tableProperty);
            }
        }
    }

    /**
     * @param array $schema
     * 
     * @return bool 
     */
    public function createTable($schema)
    {
        foreach($schema as $tableName => $property)
        $this->db->create($tableName)->columns($property)->execute();
    }
}