<?php
namespace PutaoCloud\Tests;
require_once("../../vendor/autoload.php");
use PutaoCloud\CloudManage as CloudManage;

//callable,上传完成后会回调此方法
//$ret 上传类
//$err 上传状态,如果不为空说明上传错误,如果为空,用$ret->result查看上传结果
$c1 = function($ret,$err){
   // var_dump($err);
   // var_dump($ret->result);
};
//cloud server
$server = "http://127.0.0.1:8081";
$appid = "1001";
$accessKey = "69025f5e6c7dd0c1157e0daf94e9cef5";
$secretKey = "fce1f6bb6fcfd4fad5d98ee48b441abc";
$file = "/home/dsby/zh-hans_windows_xp_professional_with_service_pack_3_x86_cd_vl_x14-74070.iso";
$cm = new CloudManage($server,$appid,$accessKey,$secretKey);
$cm->registerCallBack($c1)
	->upload($file);
