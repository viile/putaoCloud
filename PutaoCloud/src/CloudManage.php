<?php
namespace PutaoCloud;

use PutaoCloud;

class CloudManage
{
	private $server;
	private $accessKey;
	private $secretKey;
	private $appid;
	private $cloudUpload;
	private $cloudProcessing;
	private $cloudAuth;
	private $cloudStorage;

	function __construct($server,$appid,$accessKey,$secretKey)
	{
		$this->server = $server;
		$this->accessKey = $accessKey;
		$this->secretKey = $secretKey;
		$this->appid = $appid;
	}
}
