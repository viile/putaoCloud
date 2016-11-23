<?php
namespace PutaoCloud\Processing;

use PutaoCloud;
use PutaoCloud\Processing\CloudException as CloudException;

class CloudTransfer
{
	private $address;
	private $data;

	public function __construct($address,$data)
	{
		if(!$address)throw new CloudException("reqAddressIsNull");
		if(!$data)throw new CloudException("reqDataIsNull");
		$this->address = $address;
		$this->data = $data;
	}

	public function send() : array
	{
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $this->address);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $this->data);
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 15);
		$res = curl_exec($curl);
		$no = curl_errno($curl);
		if ($no > 0) {
			print_r(curl_error($curl));
			throw new CloudException("networkError");
		}
		curl_close($curl);
		//var_dump($res);
		$result = json_decode($res, true);
		//var_dump($result);
		//if (json_last_error() > 0) {
		//	throw new CloudException("jsonParseError");
		//}
		return $result;	
	}
}
