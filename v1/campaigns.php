<?php
	require_once 'campaignapi.php';
	// Requests from the same server don't have a HTTP_ORIGIN header
	if (!array_key_exists('HTTP_ORIGIN', $_SERVER)) {
	    $_SERVER['HTTP_ORIGIN'] = $_SERVER['SERVER_NAME'];
	}
	
	var_dump($_POST);
	try {
		if($_SERVER["REQUEST_METHOD"] == "POST"){
			$API = new campaignapi($_REQUEST['request'], $_SERVER['HTTP_ORIGIN'],$_POST,$_SERVER["REQUEST_METHOD"]);
		}else{
	    	$API = new campaignapi($_REQUEST['request'], $_SERVER['HTTP_ORIGIN'],$_GET,$_SERVER["REQUEST_METHOD"]);
		}
	    echo $API->processAPI();
	} catch (Exception $e) {
	    echo json_encode(Array('error' => $e->getMessage()));
	}
?>