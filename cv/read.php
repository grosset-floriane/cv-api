<?php
$requestMethod = $_SERVER["REQUEST_METHOD"];
include('../class/ReadCv.php');
$api = new ReadCv();
switch($requestMethod) {
	case 'GET':
		$cvId = '';	
		if($_GET['id']) {
			$cvId = $_GET['id'];
		} else {
			$cvId = 1;
		}
		$api->getFullCvData($cvId);
		break;
	default:
	header("HTTP/1.0 405 Method Not Allowed");
	break;
}
?>