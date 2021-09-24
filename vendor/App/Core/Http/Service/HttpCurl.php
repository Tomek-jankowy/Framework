<?php

namespace App\Core\Http\Service;

use App\Core\AppException\AppException;

class HttpCurl
{
    /**
     * @var string
     */
    protected $method;

    /**
     * @var string
     */
    protected $url;

    /**
     * @var array
     */
    protected $data;

    /**
     * @var string
     */
    protected $typeGet;

    /**
     * @var array
     */
    const METHOD = ['POST', 'GET', 'PUT'];

    /**
     * @param string $url
     * @param string $method -  GET | POST | PUT
     * @param array $data
     * @param string $typeGet - / | ?
     */
    public function __construct(string $url, string $method, array $data = [], $typeGet = '/')
    {
        try{
            if(!in_array($method, self::METHOD)){
                throw new AppException("podana mtoda nie jest prawidłowa", 0100, [__FILE__, __CLASS__, __METHOD__, __LINE__]);
            }
            if(!is_array($data)){
                throw new AppException("nie prawidłowy url", 0101, [__FILE__, __CLASS__, __METHOD__, __LINE__]);
            }
            if($typeGet !== '/' || $typeGet !== '?'){
                throw new AppException("nie prawidłowy url", 0101, [__FILE__, __CLASS__, __METHOD__, __LINE__]);
            }
        }catch( AppException $e){
            message($e->getMessage(), ERROR);
            exit;
        }
        
        $this->typeGet = $typeGet;
        $this->method = $method;
        $this->url = $url;
        $this->data = $data;

        $this->start();

    }

    /**
     * @return void
     */
    protected function start() :void
    {
        $this->setCurl();
        $this->setMethod($this->method);
    }

    /**
     * @return void
     */
    protected function setCurl() :void
    {
        $this->curl = curl_init();
    }

    /**
     * @param string $method
     * @return void
     */
    protected function setMethod($method) :void
    {
        switch($method){
            case 'POST':
                $this->setPOST();
                break;
            case 'GET':
                $this->setGET();
                break;
        }
    }

    /**
     * @return void
     */
    protected function setPOST() :void
    {
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, http_build_query($this->data));
    }

    /**
    * @return void
    */
    protected function setGET()
    {
        $this->url .= (substr($this->url, -1) === '/')? '' : '/';
        $this->url .= http_build_query($this->data);
    }

    /**
    * @return object|array
    */
    public function send()
    {
        curl_setopt($this->curl, CURLOPT_URL,$this->url);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);

        hook_pre_send_curl($this->curl);

        $result = curl_exec($this->curl);
        curl_close ($this->curl);
        return $result;
    }
}