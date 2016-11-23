<?php
namespace PutaoCloud\Processing;

use PutaoCloud;

class CloudErrorCode
{
    public static $errorcode = [
        'filenameIsNull' => 42701,
        'fileNotExist' => 42702, 
		'appidNotSet' => 42703,
		'secretKeyNotSet' => 42704,
		'accessKeyNotSet' => 42705,
		'handleFail' => 42706,
		'reqAddressIsNull' => 42707,
		'reqDataIsNull' => 42707,
		'networkError' => 42708,
		'jsonParseError' => 42709,
    ];

    public static $errorMsg = [
        'filenameIsNull' => '文件名不能为空',
        'fileNotExist' => '文件不存在或无权限访问',
		'appidNotSet' => 'appid为空',
		'secretKeyNotSet' => 'secret key 为空',
		'accessKeyNotSet' => 'access key 为空',
		'HandleFail' => '操作失败',
		'reqAddressIsNull' => '请求地址为空',
		'reqDataIsNull' => '请求数据为空',
		'networkError' => '网络错误',
		'jsonParseError' => 'json解析失败',
    ];

    public static function err($flag)
    {
        $errCode = self::$errorcode[$flag] ?? 42700;
        $errMsg = self::$errorMsg[$flag] ?? "Undefined Error";
        return [$errCode,$errMsg];
    }
}
