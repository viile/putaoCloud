<?php
namespace PutaoCloud\Upload;

use PutaoCloud;
use PutaoCloud\Processing\CloudTransfer as CloudTransfer;
use PutaoCloud\Processing\CloudException as CloudException;

class CloudUpload
{
	private $file;
	private $filesize;
	private $filename;
	private $info;
	private $cloudManage;

	public function __construct($cloudManage,string $file,string $filename)
	{
		$this->cloudManage = $cloudManage;
		$this->file = $file;
		$this->filename = $filename;
	}

	public function fileCheck() : CloudUpload
	{
		$this->filesize = filesize($this->file);
            $req = [
                'size' => $this->filesize,
                'sha1' => sha1_file($this->file),
                'filename' => $this->filename,
                'appid' => $this->cloudManage->appid(),
                'uploadToken' => $this->cloudManage->cloudAuth->uploadToken(),
            ];
            $fd = new CloudTransfer($this->cloudManage->checkFileURL(),json_encode($req));
            $res = $fd->send();
            if($res['error_code'])throw new CloudException($res['error_code']);
		$this->info = $res;	
		return $this;
	}

	public function onceUpload()
	{
                if($this->info["begin"] == $this->filesize)return $this->info;
		$fd = fopen($this->file,"r");
		fseek($fd,$this->info["begin"]);
		$filiesize = $this->filesize - $this->info["begin"];
		$f = function($curl,$fd,$size){
			return fread($fd,$size);
		};
		$curl = curl_init();
		//var_dump($this->cloudManage->uploadURL());
		curl_setopt($curl, CURLOPT_URL, $this->cloudManage->uploadURL());
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HTTPHEADER, [
			"yun_store: ".$this->info["key"],
			"Content-Length: ".$filiesize,
		]);
		curl_setopt($curl, CURLOPT_INFILE ,$fd);
		curl_setopt($curl, CURLOPT_READFUNCTION ,$f);
		//var_dump($curl);
		$res = curl_exec($curl);
		$no = curl_errno($curl);
		if ($no > 0) {
			print_r(curl_error($curl));
			throw new CloudException("networkError");
		}
		curl_close($curl);
		$result = json_decode($res, true);
		//if (json_last_error() > 0) {
		//	throw new CloudException("jsonParseError");
		//}
		return $result;	
	}
	
	public function upload()
	{
                if($this->info["begin"] == $this->filesize)return $this->info;
		$fd = fopen($this->file,"r");
		//var_dump($this->info);
		//return;
                while(true){
                    fseek($fd,$this->info["begin"]);
                    $buffer = fread($fd, 1024 * 1024);
                    $size = strlen($buffer);
                   // var_dump($size);
                    $curl = curl_init();
                    //var_dump($this->cloudManage->uploadURL());
                    curl_setopt($curl, CURLOPT_URL, $this->cloudManage->uploadURL());
                    curl_setopt($curl, CURLOPT_POST, true);
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $buffer);
                    curl_setopt($curl, CURLOPT_HEADER, false);
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($curl, CURLOPT_HTTPHEADER, [
                            "yun_store: ".$this->info["key"],
                            'Content-Length: '.$size
                    ]);
                    //var_dump($curl);
                    $res = curl_exec($curl);
                    $no = curl_errno($curl);
                    if ($no > 0) {
                            print_r(curl_error($curl));
                            throw new CloudException("networkError");
                    }
                    curl_close($curl);
                   // var_dump($res);
                    $this->info = json_decode($res, true);
                    //var_dump($this->info);
                    ///return;
                    //if (json_last_error() > 0) {
                   //         throw new CloudException("jsonParseError");
                   // }
                    if($this->info["error_code"] != 0)
                        break;
                    if($this->info["filestate"] == 1)
                        break;
		}
		return $this->info;	
	}
}
