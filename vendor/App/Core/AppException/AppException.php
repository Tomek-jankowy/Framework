<?php

namespace App\Core\AppException;

use Exception;
use Throwable;

class AppException extends Exception implements Throwable
{
    protected $message = 'Unknown exception';
    private   $string;
    protected $code = 0;
    protected $file;
    protected $line;
    private   $trace;
    private   $previous;

    public function __construct($message = '', $code = 0, $rout = '', Throwable $previous = null)
    {
        parent::__construct();
        //$traceS = '<br>File: '.$rout[0].'<br>Class: '.$rout[1].'<br>Method: '.$rout[2].'<br> Line: '.$rout[3];
        $this->message = "New exception $message <br> Code exception $code in ";//.$traceS;
        $this->code = $code;
        $this->previous = $previous;
    }
}