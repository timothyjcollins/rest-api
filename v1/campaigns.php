<?php
	require_once 'campaignapi.php';
echo phoinfo();
//require 'vendor/autoload.php';
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
//$sharedConfig = [
//    'region'  => 'us-west-2',
//    'version' => 'latest'
//];
//$sdk = new Aws\Sdk($sharedConfig);					
//$s3Client = $sdk->createS3();
//$result = $s3Client->putObject([
//    'Bucket' => 'userstoriesimages',
//    'Key'    => 'console.aws.amazon.com/s3/home?region=us-west-2',
//    'Body'   => $_FILES["fileToUpload"]
//]);
			        $filename = $target_file;
			        $uploadOk = 1;
				}
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