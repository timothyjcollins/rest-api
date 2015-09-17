<?php
	require_once 'api.class.php';
	class storiesapi extends api
	{
	    protected $User;
		protected $args;
	
	    public function __construct($request, $origin,$args) {
	    	parent::__construct($request);
			// Abstracted out for example
	        $this->User = "TEST";	
			$arg_arr = explode("&",$args);
			$arg_array = Array();
			foreach ($arg_arr as $arg_line) {
				$arg_line_elem = explode("=",$arg_line);
				$arg_array[$arg_line_elem[0]] = $arg_line_elem[1];
			}
			$this->args = $arg_array;    
			if($this->args["apikey"] != "123456"){
				throw new Exception('No or Bad API Key provided');
			}
		}
		protected function submit(){
			$camid = $this->args["camid"];
			$title = $this->args["title"];
			$desc = $this->args["desc"];
			$video = $this->args["video"];
			$image = $this->args["image"];
			$text = $this->args["text"];
			$fname = $this->args["fname"];
			$lname = $this->args["lname"];
			$address1 = $this->args["address1"];
			$address2 = $this->args["address2"];
			$country = $this->args["country"];
			$city = $this->args["city"];
			$email = $this->args["email"];
			$optin = $this->args["optin"];
			$state = $this->args["state"];
			$sub_at = $this->args["sub_at"];
			$pub_at = $this->args["pub_at"];
			$flagged = $this->args["flagged"];
			$moderated = $this->args["moderated"];
			$nameshown = $this->args["nameshown"];
			$capturing = $this->args["capturing"];
			$likes = $this->args["likes"];
			if($video != ""){
				$video_arr = explode("[",$video);
				$video_arr2 = explode(",",$video_arr[1]);
				$video_title = $video_arr2[0];
				$video_caption = $video_arr2[1];
				$video_arr3 = explode("]", $video_arr2[2]);
				$video_link = $video_arr3[0];
			}			
			if($image != ""){
				$image_arr = explode("[",$video);
				$image_arr2 = explode(",",$video_arr[1]);
				$image_caption = $video_arr2[0];
				$image_arr3 = explode("]", $image_arr2[2]);
				$image_link = $image_arr3[0];
			}			
			
			$sql = "insert into story (campaign_id,title,description,first_name,last_name,address_1,address_2,country_id,city,email,optin,";
			$sql .= "state_id,submitted_at,published_at,isflagged,likes) values (";
			$sql .= $camid . ",";
			$sql = "'" . $title . "',";
			$sql = "'" . $desc . "',";
			$sql = "'" . $fname . "',";
			$sql = "'" . $lname . "',";
			$sql = "'" . $address1 . "',";
			$sql = "'" . $address2 . "',";
			$sql = "'" . $country . "',";
			$sql = "'" . $city . "',";
			$sql = "'" . $email . "',";
			$sql = "'" . $optin . "',";
			$sql = "'" . $state . "',";
			$sql = "'" . $sub_at . "',";
			$sql = "'" . $pub_at . "',";
			$sql = "'" . $flagged . "',";
			$sql = "'" . $likes . "')";
			
			$story_id = "123";
			
			if($video != ""){
				$sql = "insert into story_video (story_id,title,caption,link) values (";
				$sql .= $story_id . ",";
				$sql .= "'" . $video_title . "',";
				$sql .= "'" . $video_caption . "',";
				$sql .= "'" . $video_link . "')";
			}
			if($image != ""){
				$sql = "insert into story_image (story_id,content) values (";
				$sql .= $story_id . ",";
				$sql .= "'" . $image_caption . "',";
				$sql .= "'" . $image_link . "')";
			}
			$sql = "insert into story_text (story_id,content) values (";
			$sql .= $story_id . "," ;
			$sql .= "'" . $text . "')";
											
			return '{"SUCCESS" : "YES"}';
		}
		protected function update(){
			$story_id = $this->args["story_id"];
			$camid = $this->args["camid"];
			$title = $this->args["title"];
			$desc = $this->args["desc"];
			$video = $this->args["video"];
			$image = $this->args["image"];
			$text = $this->args["text"];
			$fname = $this->args["fname"];
			$lname = $this->args["lname"];
			$address1 = $this->args["address1"];
			$address2 = $this->args["address2"];
			$country = $this->args["country"];
			$city = $this->args["city"];
			$email = $this->args["email"];
			$optin = $this->args["optin"];
			$state = $this->args["state"];
			$sub_at = $this->args["sub_at"];
			$pub_at = $this->args["pub_at"];
			$flagged = $this->args["flagged"];
			$moderated = $this->args["moderated"];
			$nameshown = $this->args["nameshown"];
			$capturing = $this->args["capturing"];
			$likes = $this->args["likes"];
			return '{"SUCCESS" : "YES"}';
		}
		protected function search(){
			$keywords = $this->args["keywords"];
			$filter = $this->args["filter"];
			$size = $this->args["size"];
			$camid = $this->args["camid"];
			
			return '{"STORY_ID" : "123"}';
		}
		protected function approve_story(){
			$story_id = $this->args["story_id"];
			
			return '{"SUCCESS" : "YES"}';
		}
		protected function disapprove_story(){
			$story_id = $this->args["story_id"];
			
			return '{"SUCCESS" : "YES"}';
		}
		protected function delete_story(){
			$story_id = $this->args["story_id"];
			
		 	return '{"SUCCESS" : "YES"}';	
		}
		protected function summary_list(){
			$count = $this->args["count"];
			$sort = $this->args["sort"];
			$camid = $this->args["camid"];

		 	return '{"STORY_ID" : "123" ["TITLE" : "XYZ", "DESCRIPTION" : "XYZ", "FIRST_NAME" : "XYZ", "LAST_NAME" : "XYZ"}';	
		}
		protected function long_list(){
			$count = $this->args["count"];
			$sort = $this->args["sort"];
			$camid = $this->args["camid"];

		 	return '{"STORY_ID" : "123" ["TITLE" : "XYZ", "DESCRIPTION" : "XYZ", "FIRST_NAME" : "XYZ", "LAST_NAME" : "XYZ"}';	
		}
		protected function like_story(){
			$story_id = $this->args["story_id"];
			$user_id = $this->args["user_id"];
			
			$sql = "update story set likes = likes + 1 where story_id = " . $story_id;
			
			$sql = "insert into story_likes (story_id,user_id,timestamp) values (";
			$sql .= $story_id . ",";
			$sql .= $user_id . ",";
			$sql .= "NOW())";

		 	return '{"SUCCESS"}';	
		}
		protected function rss(){
			$camid = $this->args["camid"];
			$num_to_return = $this->args["num_to_return"];
			$sort = $this->args["sort"];			
			$rss_list = "RSS Formatted Return";
			
		 	return $rss_list;	
		}
	}
?>