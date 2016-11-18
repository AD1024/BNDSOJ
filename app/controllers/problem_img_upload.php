<?php
/*
Add by dhxh
*/

/*	requirePHPLib('form');
	
	if (!validateUInt($_GET['id']) || !($problem = queryProblemBrief($_GET['id']))) {
		become404Page();
	}
	if (!hasProblemPermission($myUser, $problem)) {
		become403Page();
	}
	
function get_extension($file){
	return pathinfo($file, PATHINFO_EXTENSION);
}

$suc=false;

if($_POST['problem_img_file_submit']=='submit'){
	if ($_FILES["problem_img_file"]["error"] > 0)
  	{
  		$errmsg = "Error: ".$_FILES["problem_img_file"]["error"];
		becomeMsgPage('<div>' . $errmsg . '</div><a href="/problem/'.$problem['id'].'/manage/imgupload">返回</a>');
  	}else{
		$imgext=get_extension($_FILES["problem_img_file"]["name"]);
		$imgext=strtolower($imgext);
		if($imgext=='jpg' or $imgext=='jpeg' or $imgext=='gif' or $imgext=='png'){
			$up_file="problem_".$problem['id']."_".md5(rand(0,100000000).time()).".".$imgext;
			$up_filename="/var/www/uoj/imgupload/".$up_file;
			move_uploaded_file($_FILES["problem_img_file"]["tmp_name"], $up_filename);
			$suc=true;
		}else{
			$errmsg = "请上传图片文件！";
			becomeMsgPage('<div>' . $errmsg . '</div><a href="/problem/'.$problem['id'].'/manage/imgupload">返回</a>');
		}
	}
}
	
?>
<?php echoUOJPageHeader(HTML::stripTags($problem['title']) . ' - 编辑 - 题目管理') ?>
<h1 class="page-header" align="center">#<?=$problem['id']?> : <?=$problem['title']?> 管理</h1>
<ul class="nav nav-tabs" role="tablist">
	<li><a href="/problem/<?= $problem['id'] ?>/manage/statement" role="tab">编辑</a></li>
	<li><a href="/problem/<?= $problem['id'] ?>/manage/managers" role="tab">管理者</a></li>
	<li><a href="/problem/<?= $problem['id'] ?>/manage/data" role="tab">数据</a></li>
	<li class="active"><a href="/problem/<?= $problem['id'] ?>/manage/imgupload" role="tab">图片上传</a></li>
	<?# Added by AD1024?>
	<li><a href="/problem/<?= $problem['id']?>/manage/imgmanage" role="tab">图片管理</a></li>
	<?# Added by AD1024?>
	<li><a href="/problem/<?=$problem['id']?>" role="tab">返回</a></li>
</ul>
<?php

if($suc==true){
	echo "<br><p>";
	echo "上传成功！图片地址:/imgupload/".$up_file;
	echo "</p><br>";
}

?>

<form action="" method="post" enctype="multipart/form-data" role="form">
  	<div class="form-group">
    		<label for="exampleInputFile">文件</label>
    		<input type="file" name="problem_img_file" id="problem_img_file">
    		<p class="help-block">请上传.jpg/.jpeg/.gif/.png文件</p>
  	</div>
	<input type="hidden" name="problem_img_file_submit" value="submit">
  	<button type="submit" class="btn btn-success">上传</button>
</form>
<?php echoUOJPageFooter() ?>
*/?>

<?php
requirePHPLib('form');
	
	if (!validateUInt($_GET['id']) || !($problem = queryProblemBrief($_GET['id']))) {
		become404Page();
	}
	if (!hasProblemPermission($myUser, $problem)) {
		become403Page();
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
			$up_file="problem_".$problem['id']."_".md5(rand(0,100000000).time()).".".$imgext;
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
