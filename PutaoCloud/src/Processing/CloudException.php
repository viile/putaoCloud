<?php
namespace PutaoCloud\Processing;

use PutaoCloud;

interface IException
{
    public function getMessage();                  
    public function getCode();                    
    public function getFile();                    
    public function getLine();                    
    public function getTrace();                   
    public function getTraceAsString();           
    public function __toString(); 
    public function __construct($flag = null);
}

abstract class CException extends \Exception implements IException
{
    protected $code;
    protected $msg;
    protected $file;
    protected $line;
    protected $trace;

    public function __construct($flag = null)
    {
        list($errCode,$errMsg) = CloudErrorCode::err($flag);
        $this->code = $errCode;
        $this->msg = $errMsg;
    }

    public function __toString()
    {
        return get_class($this) . " '{$this->message}' in {$this->file}({$this->line})\n"
            . "{$this->getTraceAsString()}";
    }
}

class CloudException extends CException{}
