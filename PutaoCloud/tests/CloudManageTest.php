<?php
namespace PutaoCloud\Tests;
require_once("../../vendor/autoload.php");
use PutaoCloud\CloudManage as CloudManage;

$cm = new CloudManage("1","2","3","4");

var_dump($cm);
