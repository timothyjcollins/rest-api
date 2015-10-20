<?php
	require_once 'api.class.php';
	class campaignapi extends api
	{
	    protected $User;
		protected $args;
		protected $link;
	
	    public function __construct($request, $origin,$args,$request_method,$filename) {
	    	parent::__construct($request);
			// Abstracted out for example
			$this->link = mysqli_connect("userstories.clltdiskvizr.us-west-2.rds.amazonaws.com", "tcollins", "enif1233", "innodb");
	        $this->User = "TEST";	
			$this->request_method = $request_method;
			$this->filename = $filename;
			if($request_method == "GET"){
				$arg_arr = explode("&",$args);
				$arg_array = Array();
				foreach ($arg_arr as $arg_line) {
					$arg_line_elem = explode("=",$arg_line);
					$arg_array[$arg_line_elem[0]] = $arg_line_elem[1];
				}
				$this->args = $arg_array; 
			}else{
				$this->args = $args; 
			}   
			if($this->args["apikey"] != "123456"){
				throw new Exception('No or Bad API Key provided');
			}
		}
		protected function submit_campaign(){
			if($this->request_method != "POST"){
				return '{"CAMPAIGN_ID" : "INCORRECT REQUEST METHOD"}';
			}
			$name = $this->args["name"];	
			$desc = $this->args["desc"];
			$cat = $this->args["cat"];
			$type = $this->args["type"];
			$ctypes = $this->args["ctypes"];
			$start = $this->args["start"];
			$end = $this->args["end"];
			$nameshown = $this->args["nameshown"];
			$capturing = $this->args["capturing"];
			$moderated = $this->args["moderated"];
			$state = $this->args["state"];
			if($nameshown != "YES" and $nameshown != "NO"){
				return '{"CAMPAIGN_ID" : "ERROR NAMESHOWN SET INCORRECTLY"}';
			}
			if($capturing != "YES" and $capturing != "NO"){
				return '{"CAMPAIGN_ID" : "ERROR CAPTURING SET INCORRECTLY"}';
			}
			if($moderated != "YES" and $moderated != "NO"){
				return '{"CAMPAIGN_ID" : "ERROR MODERATED SET INCORRECTLY"}';
			}
			if($state != "PENDING" and $state != "PUBLISHED" and $state != "INACTIVE"){
				return '{"CAMPAIGN_ID" : "ERROR STATE SET INCORRECTLY"}';
			}
			$ctypes_arr = explode(",",$ctypes);
			$video_allowed = "NO";
			$image_allowed = "NO";
			$text_allowed = "NO";
			$video_max_num = 0;
			$image_max_num = 0;
			$image_max_size = 0;
			$text_max_size = 0;
			foreach($ctypes_arr as $ctype){
				$ctype_arr = explode("[",$ctype);
				if($ctype_arr[0] == "video"){
					$video_max_num_arr = explode("]",$ctype_arr[1]);
					$video_max_num = $video_max_num_arr[0];
					$video_allowed = "YES";
				}
				if($ctype_arr[0] == "image"){
					$image_max_num_arr = explode(",",$ctype_arr[1]);
					$image_max_num = $image_max_num_arr[0];
					$image_max_size_arr = explode("]",$image_max_num_arr[1]);
					$image_max_size = $image_max_size_arr[0];
					$image_allowed = "YES";
				}
				if($ctype_arr[0] == "text"){
					$text_max_num_arr = explode("]",$ctype_arr[1]);
					$text_max_size = $text_max_num_arr[0];
					$text_allowed = "YES";
				}
			}
			$sql = "insert into innodb.Campaign (name,description,category_id,type_id,ctype_video_allowed,ctype_video_max,ctype_image_allowed,ctype_image_max,";
			$sql .= "ctype_image_resolution,ctype_text_allowed,ctype_text_max,start_date,end_date,isnameshown,iscapturing,ismoderated,state) values (";
			$sql .= "'" . $name . "',";
			$sql .= "'" . $desc . "',";
			$sql .= "'" . $cat . "',";
			$sql .= "'" . $type . "',";
			$sql .= "'" . $video_allowed . "',";
			$sql .= "'" . $video_max_num . "',";
			$sql .= "'" . $image_allowed . "',";
			$sql .= "'" . $image_max_num . "',";
			$sql .= "'" . $image_max_size . "',";
			$sql .= "'" . $text_allowed . "',";
			$sql .= "'" . $text_max_size . "',";
			$sql .= "'" . $start . "',";
			$sql .= "'" . $end . "',";
			$sql .= "'" . $nameshown . "',";
			$sql .= "'" . $capturing . "',";
			$sql .= "'" . $moderated . "',";
			$sql .= "'" . $state . "')";
			$this->link->query($sql);
			$camid = mysqli_insert_id($link);
			
			return '{"CAMPAIGN_ID" : "' . $camid . '"}';
		}
		protected function update_campaign(){
			if($this->request_method != "POST"){
				return '{"CAMPAIGN_ID" : "INCORRECT REQUEST METHOD"}';
			}
			
		 	$camid = $this->args["camid"];
		 	$name = $this->args["name"];	
			$desc = $this->args["desc"];
			$cat = $this->args["cat"];
			$type = $this->args["type"];
			$ctypes = $this->args["ctypes"];
			$start = $this->args["start"];
			$end = $this->args["end"];
			$nameshown = $this->args["nameshown"];
			$capturing = $this->args["capturing"];
			$moderated = $this->args["moderated"];
			$state = $this->args["state"];
			if($nameshown != ""){
				if($nameshown != "YES" and $nameshown != "NO"){
					return '{"CAMPAIGN_ID" : "ERROR NAMESHOWN SET INCORRECTLY"}';
				}
			}
			if($capturing != ""){
				if($capturing != "YES" and $capturing != "NO"){
					return '{"CAMPAIGN_ID" : "ERROR CAPTURING SET INCORRECTLY"}';
				}
			}
			if($moderated != ""){
				if($moderated != "YES" and $moderated != "NO"){
					return '{"CAMPAIGN_ID" : "ERROR MODERATED SET INCORRECTLY"}';
				}
			}
			if($state != ""){
				if($state != "PENDING" and $state != "PUBLISHED" and $state != "INACTIVE"){
					return '{"CAMPAIGN_ID" : "ERROR STATE SET INCORRECTLY"}';
				}
			}
			if($ctypes != ""){
				$ctypes_arr = explode(",",$ctypes);
				$video_allowed = "NO";
				$image_allowed = "NO";
				$text_allowed = "NO";
				$video_max_num = 0;
				$image_max_num = 0;
				$image_max_size = 0;
				$text_max_num = 0;
				foreach($ctypes_arr as $ctype){
					$ctype_arr = explode("[",$ctype);
					if($ctype_arr[0] == "video"){
						$video_max_num_arr = explode("]",$ctype_arr[1]);
						$video_max_num = $video_max_num_arr[0];
						$video_allowed = "YES";
					}
					if($ctype_arr[0] == "image"){
						$image_max_num_arr = explode(",",$ctype_arr[1]);
						$image_max_num = $image_max_num_arr[0];
						$image_max_size_arr = explode("]",$image_max_num_arr[1]);
						$image_max_size = $image_max_size_arr[0];
						$image_allowed = "YES";
					}
					if($ctype_arr[0] == "text"){
						$text_max_num_arr = explode("]",$ctype_arr[1]);
						$text_max_num = $text_max_num_arr[0];
						$text_allowed = "YES";
					}
				}
			}
			$sql = "update innodb.Campaign ";
			$sql .= "set ";
			if($name != ""){
				$sql .= "name = '" . $name . "',";
			}
			if($desc != ""){
				$sql .= "description = '" . $desc . "',"; 
			}
			if($cat != ""){
				$sql .= "category_id = '" . $cat . "',";
			}
			if($type != ""){
				$sql .= "type_id = '" . $type . "',";
			}
			if($video_allowed != ""){
				$sql .= "ctype_video_allowed = '" . $video_allowed . "',";
			}
			if($video_max_num != ""){
				$sql .= "ctype_video_max = '" . $video_max_num . "',";
			}
			if($image_allowed != ""){
				$sql .= "ctype_image_allowed = '" . $image_allowed . "',";
			}
			if($image_max_num != ""){
				$sql .= "ctype_image_max = '" . $image_max_num . "',";
			}
			if($image_max_size != ""){
				$sql .= "ctype_image_resolution = '" . $image_max_size . "',";
			}
			if($text_allowed != ""){
				$sql .= "ctype_text_allowed = '" . $text_allowed . "',";
			}
			if($text_max_size != ""){
				$sql .= "ctype_text_max = '" . $text_max_size . "',";
			}
			if($start != ""){
				$sql .= "start_date = '" . $start . "',";
			}
			if($end != ""){
				$sql .= "end_date = '" . $end . "',";
			}
			if($nameshown != ""){
				$sql .= "isnameshown = '" . $nameshown . "',";
			}
			if($capturing != ""){
				$sql .= "iscapturing = '" . $capturing . "',";
			}
			if($moderated != ""){
				$sql .= "ismoderated = '" . $moderated . "',";
			}
			if($state != ""){
				$sql .= "state = '" . $state . "' ";
			}
			$sql = rtrim($sql, ",");
			$sql .= " where campaign_id = " . $camid;
			$this->link->query($sql);
			
			return '{"CAMPAIGN_ID" : "' . $camid . '"}';
		}
		protected function search_campaign(){
			if($this->request_method != "GET"){
				return '{"CAMPAIGN_ID" : "INCORRECT REQUEST METHOD"}';
			}
		 	$keywords = $this->args["keywords"];
			$filter = $this->args["filter"];
			$num_per_page = $this->args["num_per_page"];
			$page_num = $this->args["page_num"];
			$camid = $this->args["camid"];
			$keywords_arr = explode(",",$keywords);
			
			$sql = "select * from innodb.Campaign where ";
			
			$sql .= "(";
			foreach($keywords_arr as $keyword){
				$sql .= " name like '%" . $keyword . "%' or ";
				$sql .= " description like '%" . $keyword . "%' or ";
			}
			$sql = rtrim($sql, "or ");
			$sql .= ")";
			
			$result = $this->link->query($sql);
			$json = "{";
			while($row = $result->fetch_array()){
				$json .= '"CAMPAIGN_ID" : "' . $row["campaign_id"] . '", "VALUES" : [';
				$json .= '"NAME" : "' . $row["name"] . '",';
				$json .= '"DESCRIPTION" : "' . $row["description"] . '"';
				$json .= ']';
			}
			$json .= "}";
			
			return '{"CAMPAIGN_ID" : "' . $json . '"}';
		}
		protected function retrieve_campaign(){
			if($this->request_method != "GET"){
				return '{"CAMPAIGN_ID" : "INCORRECT REQUEST METHOD"}';
			}
			$camid = $this->args["camid"];
			
			$sql = "select * from innodb.Campaign where campaign_id = " . $camid;
						
			$result = $this->link->query($sql);
			$json = "{";
			while($row = $result->fetch_array()){
				$json .= '"CAMPAIGN_ID" : "' . $row["campaign_id"] . '", "VALUES" : [';
				$json .= '"NAME" : "' . $row["name"] . '",';
				$json .= '"DESCRIPTION" : "' . $row["description"] . '"';
				$json .= ']';
			}
			$json .= "}";
			
			return $json;
		}
		protected function delete_campaign(){
			if($this->request_method != "POST"){
				return '{"SUCCESS" : "INCORRECT REQUEST METHOD"}';
			}
			
		 	$camid = $this->args["camid"];
		 	$sql = "upadte innodb.Campaign set deleted = 'YES' where campaign_id = " . $camid;
			$this->link->query($sql);
			return '{"SUCCESS" : "YES"}';
		}
		protected function newcategory(){
			if($this->request_method != "POST"){
				return '{"SUCCESS" : "INCORRECT REQUEST METHOD"}';
			}
			
		 	$name = $this->args["name"];
			$sql = "insert into innodb.Category (category_text) values ('" . $name . "')";
			$this->link->query($sql);
			$cat_id = mysqli_insert_id($link);
			return '{"SUCCESS" : "YES - ' . $cat_id . '"}';
		}
		protected function categories(){
			if($this->request_method != "GET"){
				return '{"INCORRECT REQUEST METHOD"}';
			}
			$sql = "select category_text from innodb.Category";
			$json = "{";
			$result = $this->link->query($sql);
			while($row = $result->fetch_array()){
				$json .= '"' . $row["category_text"] . '",';
			}
			$json = rtrim($json, ",");
			$json .= "}";
		 	return $json;	
		}
		protected function submit_story(){
			if($this->request_method != "POST"){
				return '{"SUCCESS" : "INCORRECT REQUEST METHOD"}';
			}
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
			if($this->filename != "____"){
				$image = $this->filename;	
			}
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
			
			$sql = "insert into innodb.Story (campaign_id,title,description,first_name,last_name,address_1,address_2,country_id,city,email,optin,";
			$sql .= "state_id,submitted_at,published_at,isflagged,likes) values (";
			$sql .= $camid . ",";
			$sql .= "'" . $title . "',";
			$sql .= "'" . $desc . "',";
			$sql .= "'" . $fname . "',";
			$sql .= "'" . $lname . "',";
			$sql .= "'" . $address1 . "',";
			$sql .= "'" . $address2 . "',";
			$sql .= "'" . $country . "',";
			$sql .= "'" . $city . "',";
			$sql .= "'" . $email . "',";
			$sql .= "'" . $optin . "',";
			$sql .= "'" . $state . "',";
			$sql .= "'" . $sub_at . "',";
			$sql .= "'" . $pub_at . "',";
			$sql .= "'" . $flagged . "',";
			$sql .= "'" . $likes . "')";
			$this->link->query($sql);
			$story_id = mysqli_insert_id($this->link);
			
			if($video != ""){
				$sql = "insert into innodb.Story_video (story_id,title,caption,link) values (";
				$sql .= $story_id . ",";
				$sql .= "'" . $video_title . "',";
				$sql .= "'" . $video_caption . "',";
				$sql .= "'" . $video_link . "')";
				$this->link->query($sql);
			}
			if($image != ""){
				$sql = "insert into innodb.Story_image (story_id,content) values (";
				$sql .= $story_id . ",";
				$sql .= "'" . $image_caption . "',";
				$sql .= "'" . $image_link . "')";
				echo $sql;
				$this->link->query($sql);
			}
			$sql = "insert into innodb.Story_text (story_id,content) values (";
			$sql .= $story_id . "," ;
			$sql .= "'" . $text . "')";
			$this->link->query($sql);
											
			return '{"SUCCESS" : "' . $story_id . '"}';
		}
		protected function summary_list_story(){
			if($this->request_method != "GET"){
				return '{"STORY_ID" : "INCORRECT REQUEST METHOD"}';
			}
			$count = $this->args["count"];
			$sort = $this->args["sort"];
			$camid = $this->args["camid"];
			
			$sql = "select * from innodb.Story where not deleted = 'YES' and isapproved = 'YES' and campaign_id = " . $camid . " ";
			if($sort == "ALPHA_ASC"){
				$order = "order by title asc ";
			}
			if($sort == "ALPHA_DESC"){
				$order = "order by title desc ";
			}
			if($sort == "DATE_ASC"){
				$order = "order by submitted_at asc ";
			}
			if($sort == "DATE_DESC"){
				$order = "order by submited_at desc ";
			}
			if($sort == "LIKES_ASC"){
				$order = "order by likes asc ";
			}
			if($sort == "LIKES_DESC"){
				$order = "order by likes desc ";
			}
			$sql .= $order . " limit 0," . $count;
			$result = $this->link->query($sql);
			
			$json = "{";
			while($row = $result->fetch_array()){
				$json .= '"STORY_ID" : "' . $row["story_id"];
				$json .= "[";
				$json .= '"TITLE" : "' . $row["title"] . '",';
				$json .= '"DESCRIPTION" : "' . substr($row["description"],0,250) . '",';
				$json .= '"FIRST_NAME" : "' . $row["first_name"] . '",';
				$json .= '"LAST_NAME" : "' . $row["last_name"] . '"';
				$json .= "],";
			}
			$json = rtrim($json, ",");
			$json .= "}";	
		 	return $json;
		}
		protected function long_list_story(){
			if($this->request_method != "GET"){
				return '{"STORY_ID" : "INCORRECT REQUEST METHOD"}';
			}
			$count = $this->args["count"];
			$sort = $this->args["sort"];
			$camid = $this->args["camid"];
			
			$sql = "select * from innodb.Story where not deleted = 'YES' and isapproved = 'YES' and campaign_id = " . $camid . " ";
			if($sort == "ALPHA_ASC"){
				$order = "order by title asc ";
			}
			if($sort == "ALPHA_DESC"){
				$order = "order by title desc ";
			}
			if($sort == "DATE_ASC"){
				$order = "order by submitted_at asc ";
			}
			if($sort == "DATE_DESC"){
				$order = "order by submited_at desc ";
			}
			if($sort == "LIKES_ASC"){
				$order = "order by likes asc ";
			}
			if($sort == "LIKES_DESC"){
				$order = "order by likes desc ";
			}
			$sql .= $order . " limit 0," . $count;
			$result = $this->link->query($sql);
			
			$json = "{";
			while($row = $result->fetch_array()){
				$json .= '"STORY_ID" : "' . $row["story_id"];
				$json .= "[";
				$json .= '"TITLE" : "' . $row["title"] . '",';
				$json .= '"DESCRIPTION" : "' . $row["description"] . '",';
				$json .= '"FIRST_NAME" : "' . $row["first_name"] . '",';
				$json .= '"LAST_NAME" : "' . $row["last_name"] . '"';
				$json .= "],";
			}
			$json = rtrim($json, ",");
			$json .= "}";	
		 	return $json;
		}
		protected function retrieve_story(){
			if($this->request_method != "GET"){
				return '{"STORY_ID" : "INCORRECT REQUEST METHOD"}';
			}
			$story_id = $this->args["story_id"];
			
			$sql = "select * from innodb.Story where story_id = " . $story_id;
			$result = $this->link->query($sql);
			$row = $result->fetch_array();
			
			$json = "{";
			$json .= '"STORY_ID" : "' . $row["story_id"] . ', ';
			$json .= '"VALUES" : [';
			$json .= '"TITLE" : "' . $row["title"] . '",';
			$json .= '"DESCRIPTION" : "' . $row["description"] . '",';
			$json .= '"FIRST_NAME" : "' . $row["first_name"] . '",';
			$json .= '"LAST_NAME" : "' . $row["last_name"] . '",';
			$json .= '"ADDRESS_1" : "' . $row["address_1"] . '",';
			$json .= '"ADDRESS_2" : "' . $row["address_2"] . '",';
			$json .= '"COUNTRY" : "' . $row["country"] . '",';
			$json .= '"CITY" : "' . $row["city"] . '",';
			$json .= '"OPTIN" : "' . $row["optin"] . '",';
			$json .= '"STATE" : "' . $row["state"] . '",';
			$json .= '"SUBMITTED_AT" : "' . $row["submitted_at"] . '",';
			$json .= '"PUBLISHED_AT" : "' . $row["published_at"] . '",';
			$json .= '"ISFLAGGED" : "' . $row["isflagged"] . '",';
			$json .= '"LIKES" : "' . $row["likes"] . '",';
			$json .= '"EMAIL" : "' . $row["email"] . '",';
			$json .= '"DELETED" : "' . $row["deleted"] . '"';
			$json .= "]";
			$json .= "}";	
		 	return $json;
		}
		protected function update_story(){
			if($this->request_method != "POST"){
				return '{"SUCCESS" : "INCORRECT REQUEST METHOD"}';
			}
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
			$likes = $this->args["likes"];
			$deleted = $this->args["deleted"];
			
			$sql = "update innodb.Story set ";
			if($title != ""){
				$sql .= "title = '" . $title . "', ";	
			}
			if($desc != ""){
				$sql .= "description = '" . $desc . "', ";	
			}
			if($fname != ""){
				$sql .= "first_name = '" . $fname . "', ";	
			}
			if($lname != ""){
				$sql .= "last_name = '" . $lname . "', ";	
			}
			if($address1 != ""){
				$sql .= "address_1 = '" . $address1 . "', ";	
			}
			if($address2 != ""){
				$sql .= "address_2 = '" . $address2 . "', ";	
			}
			if($country != ""){
				$sql .= "country_id = '" . $country . "', ";	
			}
			if($city != ""){
				$sql .= "city = '" . $city . "', ";	
			}
			if($email != ""){
				$sql .= "email = '" . $email . "', ";	
			}
			if($optin != ""){
				$sql .= "optin = '" . $optin . "', ";	
			}
			if($state != ""){
				$sql .= "state_id = '" . $state . "', ";	
			}
			if($pub_at != ""){
				$sql .= "published_at = '" . $pub_at . "', ";	
			}
			if($sub_at != ""){
				$sql .= "submitted_at = '" . $sub_at . "', ";	
			}
			if($flagged != ""){
				$sql .= "isflagged = '" . $flagged . "', ";	
			}
			if($likes != ""){
				$sql .= "title = '" . $likes . "', ";	
			}
			if($deleted != ""){
				$sql .= "deleted = '" . $deleted . "', ";	
			}
			$sql = rtrim($sql, ", ");
			$sql .= "where story_id = " . $story_id;
			$this->link->query($sql);
										
			return '{"SUCCESS" : "YES"}';
		}
		protected function delete_story(){
			if($this->request_method != "POST"){
				return '{"SUCCESS" : "INCORRECT REQUEST METHOD"}';
			}
			
			$story_id = $this->args["story_id"];
			$sql = "update innodb.Story set deleted = 'YES' where story_id = " . $story_id;
			$this->link->query($sql);
		 	return '{"SUCCESS" : "YES"}';	
		}
		protected function approve_story(){
			if($this->request_method != "POST"){
				return '{"SUCCESS" : "INCORRECT REQUEST METHOD"}';
			}
			$story_id = $this->args["story_id"];
			$sql = "update innodb.Story set isapproved = 'YES' where story_id = " . $story_id;
			$this->link->query($sql);
			
			return '{"SUCCESS" : "YES"}';
		}
		protected function disapprove_story(){
			if($this->request_method != "POST"){
				return '{"SUCCESS" : "INCORRECT REQUEST METHOD"}';
			}
			$story_id = $this->args["story_id"];
			$sql = "update innodb.Story set isapproved = 'NO' where story_id = " . $story_id;
			$this->link->query($sql);

			$story_id = $this->args["story_id"];
			
			return '{"SUCCESS" : "YES"}';
		}
		protected function search_story(){
			if($this->request_method != "GET"){
				return '{"SUCCESS" : "INCORRECT REQUEST METHOD"}';
			}
			$keywords = $this->args["keywords"];
			$filter = $this->args["filter"];
			$size = $this->args["size"];
			$camid = $this->args["camid"];
			
			$sql = "select * from innodb.Story where campaign_id = " . $camid . " and not deleted = 'YES' and isapproved = 'YES' and ";
			$keywords_arr = explode(",",$keywords);
			$sql .= "(";
			foreach($keywords_arr as $keyword){
				$sql .= " title like '%" . $keyword . "%' or ";
				$sql .= " description like '%" . $keyword . "%' or ";
			}
			$sql = rtrim($sql, "or ");
			$sql .= ")";
			
			$json = "{";
			
			$result = $this->link->query($sql);
			while($row = $result->fetch_array()){
				$json .= '"STORY_ID" : "' . $row["story_id"] . '", "VALUES" : [';
				$json .= '"TITLE" : "' . $row["title"] . '",';
				$json .= '"DESCRIPTION" : "' . $row["description"] . '",';
				$json .= '"FIRST_NAME" : "' . $row["first_name"] . '",';
				$json .= '"LAST_NAME" : "' . $row["last_name"] . '",';
				$json .= '"ADDRESS_1" : "' . $row["address_1"] . '",';
				$json .= '"ADDRESS_2" : "' . $row["address_2"] . '",';
				$json .= '"COUNTRY" : "' . $row["country_id"] . '",';
				$json .= '"CITY" : "' . $row["city"] . '",';
				$json .= '"OPTIN" : "' . $row["optin"] . '",';
				$json .= '"STATE" : "' . $row["state_id"] . '",';
				$json .= '"SUBMITTED_AT" : "' . $row["submitted_at"] . '",';
				$json .= '"PUBLISHED_AT" : "' . $row["published_at"] . '",';
				$json .= '"ISFLAGGED" : "' . $row["isflagged"] . '",';
				$json .= '"LIKES" : "' . $row["likes"] . '",';
				$json .= '"EMAIL" : "' . $row["email"] . '"';
				$json .= "],";
			}
			$json = rtrim($json, ",");
			$json .= "}";
			return $json;
		}
		protected function rss(){
			if($this->request_method != "GET"){
				return '{"SUCCESS" : "INCORRECT REQUEST METHOD"}';
			}
			$camid = $this->args["camid"];
			$numtoreturn = $this->args["numtoreturn"];
			$sort = $this->args["sort"];
			$host = $this->args["host"];
			$rss = "";
			
			$sql = "select * from innodb.Campaign where campaign_id = " . $camid;
			$result = $this->link->query($sql);
			$row = $result->fetch_array();
			$rss .= '<?xml version="1.0"?>';
			$rss .= '<rss version="2.0">';
			$rss .= '<channel>';
			$rss .= '<title>' . $row["name"] . '</title>';
			$rss .= '<description>' . $row["description"] . '</description>';
			$rss .= '<language>en-us</language>';
			$rss .= '<pubDate>' . $row["start_date"] . '</pubDate>';
			$rss .= '<lastBuildDate>' . $row["start_date"] . '</lastBuildDate>';
			
			$sql = "select * from innodb.Story where campaign_id = " . $camid . " "; 
			if($sort == "ALPHA_ASC"){
				$order = "order by title asc ";
			}
			if($sort == "ALPHA_DESC"){
				$order = "order by title desc ";
			}
			if($sort == "DATE_ASC"){
				$order = "order by published_at asc ";
			}
			if($sort == "DATE_DESC"){
				$order = "order by published_at desc ";
			}
			if($sort == "LIKES_ASC"){
				$order = "order by likes asc ";
			}
			if($sort == "LIKES_DESC"){
				$order = "order by likes desc ";
			}
			$sql .= $order . " limit 0," . $numtoreturn;
			
			$result = $this->link->query($sql);
			while($row = $result->fetch_array()){
				$rss .= '<item>';
				$rss .= '<title>' . $row["title"] . '</title>';
				$rss .= '<link>' . $host . '</link>';
				$rss .= '<description>' . $row["description"] . '</description>';
				$rss .= '<pubDate>' . $row["published_at"] . '</pubDate>';
				$rss .= '<guid>' . $host .'</guid>';
				$rss .= '</item>';
			}
			
			$rss .='</channel>';
			$rss .= '</rss>';
			return $rss;
		}
	}
?>