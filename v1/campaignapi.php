<?php
	require_once 'api.class.php';
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
		protected function submit_campaign(){
			$link = mysqli_connect("userstories.clltdiskvizr.us-west-2.rds.amazonaws.com", "tcollins", "enif1233", "innodb");
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
			$link->query($sql);
			$camid = mysqli_insert_id($link);
			
			return '{"CAMPAIGN_ID" : "' . $camid . '"}';
		}
		protected function update_campaign(){
			$link = mysqli_connect("userstories.clltdiskvizr.us-west-2.rds.amazonaws.com", "tcollins", "enif1233", "innodb");
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
				if($nameshown != "YES" and $namesown != "NO"){
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
			$link->query($sql);
			
			return '{"CAMPAIGN_ID" : "' . $camid . '"}';
		}
		protected function search_campaign(){
			$link = mysqli_connect("userstories.clltdiskvizr.us-west-2.rds.amazonaws.com", "tcollins", "enif1233", "innodb");
		 	$keywords = $this->args["keywords"];
			$filter = $this->args["filter"];
			$num_per_page = $this->args["num_per_page"];
			$page_num = $this->args["page_num"];
			$camid = $this->args["camid"];
			$keywords_arr = explode(",",$keywords);
			
			$sql = "select * from innodb.Camapign where ";
			
			$sql .= "(";
			foreach($keywords_arr as $keyword){
				$sql .= " name like '%" . $keyword . "%' or ";
				$sql .= " description like '%" . $keyword . "%' or ";
			}
			$sql = rtrim($sql, "or ");
			$sql .= ")";
			
			return '{"CAMPAIGN_ID" : "' . $sql . '"}';
		}
		protected function delete_campaign(){
			$link = mysqli_connect("userstories.clltdiskvizr.us-west-2.rds.amazonaws.com", "tcollins", "enif1233", "innodb");
		 	$camid = $this->args["camid"];
		 	$sql = "upadte innodb.Campaign set deleted = 'YES' where campaign_id = " . $camid;
			$link->query($sql);
			return '{"SUCCESS" : "YES"}';
		}
		protected function newcategory(){
			$link = mysqli_connect("userstories.clltdiskvizr.us-west-2.rds.amazonaws.com", "tcollins", "enif1233", "innodb");
		 	$name = $this->args["name"];
			$sql = "insert into innodb.Category (category_text) values ('" . $name . "')";
			$link->query($sql);
			$cat_id = mysqli_insert_id($link);
			return '{"SUCCESS" : "YES - ' . $cat_id . '"}';
		}
		protected function categories(){
			$link = mysqli_connect("userstories.clltdiskvizr.us-west-2.rds.amazonaws.com", "tcollins", "enif1233", "innodb");
			$sql = "select category_text from innodb.Category";
			$json = "{";
			$result = $link->query($sql);
			while($row = $result->fetch_array()){
				$json .= '"' . $row["category_text"] . '",';
			}
			$json = rtrim($json, ",");
			$json .= "}";
		 	return $json;	
		}
	}
?>