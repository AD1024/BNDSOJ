<?php
/*
Add by dhxh
*/
requirePHPLib('form');
	
	if (!UOJContext::hasBlogPermission()) {
		become403Page();
	}
	if (isset($_GET['id'])) {
		if (!validateUInt($_GET['id']) || !($blog = queryBlog($_GET['id'])) || !UOJContext::isHisBlog($blog)) {
			become404Page();
		}
	} else {
		$blog = DB::selectFirst("select * from blogs where poster = '".UOJContext::user()['username']."' and type = 'B' and is_draft = true");
	}
	
function get_extension($file){
	return pathinfo($file, PATHINFO_EXTENSION);
}

$suc=false;

if($_POST['problem_img_file_submit']=='submit'){
	if ($_FILES["problem_img_file"]["error"] > 0)
  	{
  		$errmsg = "Error: ".$_FILES["problem_img_file"]["error"];
		//becomeMsgPage('<div>' . $errmsg . '</div><a href="/problem/'.$problem['id'].'/manage/imgupload">返回</a>');
		echo $errmsg;
  	}else{
		$imgext=get_extension($_FILES["problem_img_file"]["name"]);
		$imgext=strtolower($imgext);
		if($imgext=='jpg' or $imgext=='jpeg' or $imgext=='gif' or $imgext=='png'){
			$up_file="blog_".$myUser['username']."_".md5(rand(0,100000000).time()).".".$imgext;
			$up_filename="/var/www/uoj/imgupload/".$up_file;
			move_uploaded_file($_FILES["problem_img_file"]["tmp_name"], $up_filename);
			echo "ok:/imgupload/".$up_file;
		}else{
			$errmsg = "请上传图片文件！";
			//becomeMsgPage('<div>' . $errmsg . '</div><a href="/problem/'.$problem['id'].'/manage/imgupload">返回</a>');
			echo $errmsg;
		}
	}
}else{
	$errmsg = "请上传图片文件！";
	echo $errmsg;
}
?>
