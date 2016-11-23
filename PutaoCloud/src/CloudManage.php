<?php
namespace PutaoCloud;
use PutaoCloud;
use PutaoCloud\Auth\CloudAuth as CloudAuth;
use PutaoCloud\Processing\CloudException as CloudException;
use PutaoCloud\Processing\CloudTransfer as CloudTransfer;
use PutaoCloud\Upload\CloudUpload as CloudUpload;
class CloudManage
{
    private $server;
    private $accessKey;
    private $secretKey;
    private $appid;
    private $cloudUpload;
    private $cloudProcessing;
    public $cloudAuth;
    private $cloudStorage;
    private $callbacks;
    private $err;
    private $uploadToken;
    private $upfileinfo = "/upfileinfo";
    private $lageupload = "/largeupload";
	public $result;
    public function __construct(string $server,string $appid,string $accessKey,string $secretKey)
    {
        $this->server = $server;
        $this->accessKey = $accessKey;
        $this->secretKey = $secretKey;
        $this->appid = $appid;
    }
    public function appid() : string
    {
        return $this->appid;
    }
    public function checkFileURL() : string
    {
        return $this->server . $this->upfileinfo;
    }
    public function uploadURL() : string
    {
        return $this->server . $this->lageupload;
    }
    public function registerCallBack(callable $callback) : CloudManage
    {
        if(!is_callable($callback))
        {
            throw new CloudException("callbackNotCallabe"); 
        }
        $this->callbacks[] = $callback;
        return $this;
    }
    public function doCallBack() : CloudManage
    {
        if($this->callbacks){
            foreach($this->callbacks as $callback)
            {
                if(is_callable($callback))$callback($this,$this->err);
            }
        }
        return $this;
    }
    public function upload($realpath = "",$filename = "")
    {
        try{
            if(!file_exists($realpath))
            {
                $this->err = new CloudException("fileNotExist");
                return;
            }
            $filename = strlen($filename) ? $filename : basename($realpath);
            $this->cloudAuth = new CloudAuth($this->appid,
                $this->accessKey,$this->secretKey);
            $this->uploadToken = $this->cloudAuth->uploadToken();
            $cloudUpload = new CloudUpload($this,$realpath,$filename);
            $this->result = $cloudUpload->fileCheck()->upload();
        } catch(Exception $e) {
        } catch(CloudException $e) {
            //var_dump($e);
            $this->err = $e;
        }
        $this->doCallBack();
        return;
    }
    public function download()
    {
        try{
        } catch(Exception $e) {
        } catch(CloudException $e) {
        }
    }
}
