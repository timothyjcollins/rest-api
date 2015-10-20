<?php
	require_once 'campaignapi.php';
	// Requests from the same server don't have a HTTP_ORIGIN header
	if (!array_key_exists('HTTP_ORIGIN', $_SERVER)) {
	    $_SERVER['HTTP_ORIGIN'] = $_SERVER['SERVER_NAME'];
	}
	
	try {
		$filename = "____";
		if($_SERVER["REQUEST_METHOD"] == "POST"){
			if($_POST["request"] == "submit_story"){
				$target_file = basename($_FILES["fileToUpload"]["name"]);
				$uploadOk = 1;
				$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
				// Check if image file is a actual image or fake image
				if(isset($_POST["submit"])) {
					//move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file);
			        $filename = $target_file;
			        $uploadOk = 1;
				}
				$postdata = http_build_query(
				    array(
				        'key' => 'uploads/${filename}',
				        'AWSAccessKeyId' => 'AKIAIBW6LGSGPSKAUWGQ',
				        'acl' => 'private',
				        'success_action_redirect' => 'http://localhost/',
				        'policy' => '{"expiration": "2009-01-01T00:00:00Z",  "conditions": [   {"bucket": "s3-bucket"},    ["starts-with", "$key", "uploads/"],   {"acl": "private"},  {"success_action_redirect": "http://localhost/"},   ["starts-with", "$Content-Type", ""],   ["content-length-range", 0, 1048576] ]}',
				        'signature' => 'BkTC4k7XnfYZ91UtE9Z998XbbKA=',
				        'Content-Type' => 'image/jpeg',
				        'file' => $_FILES["fileToUpload"]
				    )
				);
				$opts = array('http' =>
				    array(
				        'method'  => 'POST',
				        'header'  => 'Content-type: application/x-www-form-urlencoded',
				        'content' => $postdata
				    )
				);
				$context  = stream_context_create($opts);
				$result = file_get_contents('http://userstoriesimages.s3-website-us-west-2.amazonaws.com', false, $context);
				print_r($result);
			}
			$API = new campaignapi($_REQUEST['request'], $_SERVER['HTTP_ORIGIN'],$_POST,$_SERVER["REQUEST_METHOD"],$filename);
		}else{
	    	$API = new campaignapi($_REQUEST['request'], $_SERVER['HTTP_ORIGIN'],$_SERVER["QUERY_STRING"],$_SERVER["REQUEST_METHOD"],$filename);
		}
	    echo $API->processAPI();
	} catch (Exception $e) {
	    echo json_encode(Array('error' => $e->getMessage()));
	}
?>