<?php

namespace App\Core\Database\Service;

use PDO;
use PDOException;
use App\Core\AppException\AppException;
use App\Core\Settings\Service\SettingsService;
use Exception;

class DatabaseService 
{
    /**
     * @var App\Core\Settings\Service
     */
    protected $settings;

    /**
     * @var string type of query
     */
    protected $query = '';

    /**
     * @var string table's name
     */
    protected $table = '';

    /**
     * @var string fields to be downloaded from the database
     */
    protected $fields = '';

    /**
     * @var array $connectionArray 
     */
    protected array $conditionArray = [];

    /**
     * @var array $connectionText 
     */
    protected string $conditionText = '';
    
    /**
     * @var string $sortText
     */
    protected string $sortText = '';

    /**
     * @var string $columns
     */
    protected string $columns = '';

    public function __construct()
    {
        $this->settings = new SettingsService;
        $this->setDatabase();
    }

    /**
     * @param array $settings;
     * 
     * @return bool 
     */
    private function connect($settings) :bool
    {
        try{
            $connection = new PDO("{$settings['engine']}:host={$settings['host']};dbname={$settings['databaseName']};charset=utf8",
            $settings['userName'],
            $settings['passowrd'],
            [
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]);
            $this->connect = $connection;
            return true;
        }catch(PDOException $error){
            message("I am so sorry somethimg wemt wrong :".$error, ERROR);
            return false;
        }
    }

    /**
     * @param string $dbName default db is Main
     * 
     * @return bool true when success made connet or false when throw PDO exception
     */
    public function setDatabase( string $dbName = 'Main' ) :bool
    {
        try{
            $dbConfig = $this->settings->loadDatabaseConfig($dbName);
            if(empty($dbConfig)){
                throw new AppException("This database no exist: '$dbName'", 0001, [__FILE__, __CLASS__, __METHOD__, __LINE__]);
            }
            if($this->connect($dbConfig) === false){
                throw new AppException("This connect can not by use", 0002, [__FILE__, __CLASS__, __METHOD__, __LINE__]);
            }
            $this->dbConfig = $dbConfig;
        }catch(AppException $e){
            message($e->getMessage(), ERROR);
            return false;
        }
        return true;
    }

    /**
     * @param string $table - Table name of database
     * 
     * @return object $this
     */
    public function select( string $table)
    {
        $this->query = 'SELECT';
        $this->table = $table;
        return $this;
    }

    public function delete( string $table)
    {
        $this->query = 'DELETE';
        $this->table = $table;
        return $this;
    }
    public function insert( string $table)
    {
        $this->query = 'INSERT';
        $this->table = $table;
        return $this;
    }
    public function update( string $table)
    {
        $this->query = 'UPDATE';
        $this->table = $table;
        return $this;
    }

    public function create( string $table)
    {
        $this->query = 'CREATE';
        $this->table = $table;
        return $this;
    }
    
    public function dataToWrite( array $data )
    {
        $this->dataText = '';
        $this->dataArray = $data;
        $dataCount = count($data);
        $i=0;
        foreach($data as $key => $v){
            $i++;
            $this->dataText .= " $key = :$key ";
            if($i < $dataCount){
                $this->dataText .= ',';
            }
        }
        return $this;
    }

    /**
     * @param string $field name of field or fields when field, field
     * 
     * @return object $this
     */
    public function field( string $field = '*' )
    {
        if( !isset( $this->filds ) ){
            $this->fields = $field;
        }else{
            $this->fields .= ', '.$field;
        }
        return $this;
    }

    /**
     * @param string $key - field name
     * @param mixed $value - value of condition
     * @param string $criterion - =, <, >, <=, =>, %, in (if value is array) 
     * 
     * @return object $this
     */
    public function condition( string $key, mixed $value, string $criterion = '=' )
    {
        $this->conditionArray[$key] = $value;

        if(empty($this->conditionText)){
            $this->conditionText = ' WHERE ';
        }
        else{
            $this->conditionText .= ' AND ';
        }
        $this->conditionText .= "$key $criterion :$key";

        return $this;
    }
    
    /**
     * @param string $key The table's name on which it will be sorted
     * @param string $value ASC DESC
     * 
     * @return object $this
     */
    public function sort( string $key, string $value)
    {
        $this->sortText = "ORDER BY $key $value ";
        return $this;
    }

    /**
     * @param array $schema
     * 
     * @return object $this;
     */
    public function columns(array $schema)
    {
        $this->columns = "";
        foreach($schema as $columnName => $property){
            if( is_array($property)){
                $type = ($property['type'] !== 'VARCHAR')? $property['type'] : $property['type']."(".$property['lenght'].")" ;
                $not_null = ($property['not null'])? "not null" : '';
                $comma = (empty($this->columns))? '' : ", ";
                $this->columns .= "$comma $columnName $type $not_null";
            }  
        }
        return $this;
    }

    /**
     * @return object $this
     */
    public function execute()
    {
        try{
            $connectionText = '';
            if( !isset($this->table) && empty($this->table) ){
                throw new AppException("Table name isn't set", 0003, [__FILE__, __CLASS__, __METHOD__, __LINE__]);
            }
            $table = $this->table;

            if(!isset( $this->fields ) && empty( $this->fields ) ){
                $fields = $this->field();
            }
            $fields =  $this->fields;

            $connectionText = '';
            if( !empty( $this->conditionText ) ){
                $connectionText = $this->conditionText;
            }

            $sortText = '';
            if( !empty($this->sortArray) ){
                $sortText = $this->sortText;
            }

            if( !empty( $this->dataText ) && !empty( $this->dataArray ) ){
                $dataText = $this->dataTest;
                $this->conditionArray = array_merge( $this->conditionArray, $this->dataArray );
            }

            $columns = '';
            if(!empty( $this->columns )){
                $columns = $this->columns;
            }

        }catch( AppException $e){
            message($e->getMessage(), ERROR);
            return false;
        }

        try{
            switch($this->query){
                case 'SELECT':
                    $query = $this->connect->prepare("SELECT $fields FROM $table $connectionText $sortText");
                    break;
                case 'DELETE':
                    $query = $this->connect->prepare("DELETE FROM $table $connectionText");
                    break;
                case 'INSERT':
                    $keys = implode( ', ', array_keys( $this->dataArray ) );
                    $values = implode(', ', array_values( $this->dataArray ) );
                    $query = $this->connection->query("INSERT INTO $table($keys) VALUE ($values)");
                    break;
                case 'UPDATE':
                    $query = $this->connect->prepare("UPDATE $table SET $dataText $connectionText");
                    break;
                case 'CREATE':
                    $columns = '';
                    if(!isset($this->columns) || empty( $this->columns )){
                        throw new AppException("Table columns isn't set", 0003, [__FILE__, __CLASS__, __METHOD__, __LINE__]);
                    }
                    $columns = $this->columns;
                    $db = $this->dbConfig['databaseName'];
                    
                    $query = $this->connect->prepare("CREATE TABLE IF NOT EXISTS $db.$table ($columns)");
                    break;
                case empty($this->query):
                    return false;
            }
            
            if( !$query->execute($this->conditionArray) ){
                throw new PDOException("Table name isn't set", 0005);
            }

        }catch(PDOException $e){
            message($e->getMessage(), ERROR);
            return false;
        }
        return $this;
    }

    /**
     * @return array|false 
     */
    public function fetchAllArray()
    {
        $result =  $this->connect->fetchAll(PDO::FETCH_ASSOC);
        if(!$result){
            return false;
        }
        return $result;
    }

    /**
     * @return array|false 
     */
    public function fetchAllObject()
    {
        $result =  $this->connect->fetchAll(PDO::FETCH_CLASS);
        if(!$result){
            return false;
        }
        return $result;
    }

    /**
     * @return array|false 
     */
    public function fetchAllArrayNum()
    {
        $result =  $this->connect->fetchAll(PDO::FETCH_NUM);
        if(!$result){
            return false;
        }
        return $result;
    }

    /**
     * @param string $tableName
     * 
     * @return bool 
     */
    public function tableExist($tableName)
    {
        try {
            $result = $this->connect->query("SELECT 1 FROM $tableName LIMIT 1");
        } catch (Exception $e) {
            return FALSE;
        }

        if($result === FALSE){
            return true;
        }

        return false;
    }
    
}