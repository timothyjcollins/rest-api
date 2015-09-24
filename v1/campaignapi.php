<?php
	require_once 'api.class.php';
	mysql_connect('userstories.clltdiskvizr.us-west-2.rds.amazonaws.com', 'tcollins', 'enif1233');
    mysql_select_db('innodb');
	class campaignapi extends api
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
			if($nameshown != "YES" and $namesown != "NO"){
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
			$sql = "insert into campaign (name,description,category_id,type_id,ctype_video_allowed,ctype_video_max,ctype_image_allowed,ctype_image_max,";
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
			
			return '{"CAMPAIGN_ID" : "123"}';
		}
		protected function update(){
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
			if($nameshown != "YES" and $namesown != "NO"){
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
			$sql = "update campaign ";
			$sql .= "set name = '" . $name . "',";
			$sql .= "description = '" . $desc . "',"; 
			$sql .= "category_id = '" . $cat . "',";
			$sql .= "type_id = '" . $type . "',";
			$sql .= "ctype_video_allowed = '" . $video_allowed . "',";
			$sql .= "ctype_video_max = '" . $video_max_num . "',";
			$sql .= "ctype_image_allowed = '" . $image_allowed . "',";
			$sql .= "ctype_image_max = '" . $image_max_num . "',";
			$sql .= "ctype_image_resolution = '" . $image_max_size . "',";
			$sql .= "ctype_text_allowed = '" . $text_allowed . "',";
			$sql .= "ctype_text_max = '" . $text_max_size . "',";
			$sql .= "start_date = '" . $start . "',";
			$sql .= "end_date = '" . $end . "',";
			$sql .= "isnameshown = '" . $nameshown . "',";
			$sql .= "iscapturing = '" . $capturing . "',";
			$sql .= "ismoderated = '" . $moderated . "',";
			$sql .= "state = '" . $state . "' ";
			$sql .= "where camapign_id = " . $camid;
			
			return '{"CAMPAIGN_ID" : "' . $camid . '"}';
		}
		protected function search(){
		 	$keywords = $this->args["keywords"];
			$filter = $this->args["filter"];
			$num_per_page = $this->args["num_per_page"];
			$page_num = $this->args["page_num"];
			$camid = $this->args["camid"];
			$keywords_arr = explode(",",$keywords);
			
			return '{"CAMPAIGN_ID" : "123"}';
		}
		protected function delete(){
		 	$camid = $this->args["camid"];
		 	$sql = "delete from camapign where campaign_id = " . $camid;
			mysql_query($sql);
			return '{"SUCCESS" : "YES"}';
		}
		protected function newcategory(){
		 	$name = $this->args["name"];
			$sql = "select category_id from nnodb.Category order by category_id desc";
			$result = mysql_query($sql);
			$row = mysql_fetch_array($result);
			$cat_id = $row["category_id"];
			$cat_id = $cat_id + 1;
			$sql = "insert into category (category_id,category_text) values (1,'" . $name . "')";
			mysql_query($sql);
			return '{"SUCCESS" : "YES - ' . $cat_id . '"}';
		}
		protected function categories(){
			$sql = "select category_text from Category";
			$json = "{";
			$result = mysql_query($sql);
			while($row = mysql_fetch_array($sql)){
				$json .= '"' . $row["category_text"] . '",';
			}
			$json = rtrim($json, ",");
			$json .= "}";
		 	return $json;	
		}
	}
?>