<?php
namespace PutaoCloud\Auth;

use PutaoCloud;
use PutaoCloud\Processing\CloudException as CloudException;

class CloudAuth
{
    private $appid;
    private $accessKey;
    private $secretKey;
    private $uploadToken;

    public function __construct($appid,$accessKey,$secretKey)
    {
        if(!$appid)throw new CloudException("appidNotSet");
        if(!$secretKey)throw new CloudException("secretKeyNotSet");
        if(!$accessKey)throw new CloudException("accessKeyNotSet");
        $this->accessKey = $accessKey;
        $this->secretKey = $secretKey;
        $this->appid = $appid;
    }

    public function base64_urlSafeEncode($data)
    {
        $find = array('+', '/');
        $replace = array('-', '_');
        return str_replace($find, $replace, base64_encode($data));
    }
    public function uploadToken() : string
    {
		if($this->uploadToken)return $this->uploadToken;
        $request = [
            'deadline' => time() + 24 * 3600
        ];	
        $putPolicy = json_encode($request);
        $encodedPutPolicy = $this->base64_urlSafeEncode($putPolicy);
        $sign = hash_hmac('sha1', $encodedPutPolicy,$this->secretKey, true);
        $encodedSign = $this->base64_urlSafeEncode($sign);
        $this->uploadToken = $this->accessKey . ':' . $encodedSign . ':' . $encodedPutPolicy;
        return $this->uploadToken;
    }
}
