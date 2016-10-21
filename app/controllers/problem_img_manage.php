<?php
/*
* Created by AD1024
* */
		requirePHPLib('form');
		if (!validateUInt($_GET['id']) || !($problem = queryProblemBrief($_GET['id']))) {
			become404Page();
		}
		if (!hasProblemPermission($myUser, $problem)) {
			become403Page();
		}
		if(isset($_POST["Removal"])){
		$filedir="/var/www/uoj/imgupload/";
		foreach ($_REQUEST['rm'] as $del) {
			// Delete certain file
			unlink($filedir.$del);
			echo $filedir.$del;
		}
	}

?>

<?php echoUOJPageHeader(HTML::stripTags($problem['title']) . ' - 编辑 - 题目管理') ?>
<h1 class="page-header" align="center">#<?=$problem['id']?> : <?=$problem['title']?> 管理</h1>
<ul class="nav nav-tabs" role="tablist">
	<li><a href="/problem/<?= $problem['id'] ?>/manage/statement" role="tab">编辑</a></li>
	<li><a href="/problem/<?= $problem['id'] ?>/manage/managers" role="tab">管理者</a></li>
	<li><a href="/problem/<?= $problem['id'] ?>/manage/data" role="tab">数据</a></li>
	<li><a href="/problem/<?= $problem['id'] ?>/manage/imgupload" role="tab">图片上传</a></li>
	<li class="active"><a href="/problem/<?= $problem['id']?>/manage/imgmanage" role="tab">图片管理</a></li>
	<li><a href="/problem/<?=$problem['id']?>" role="tab">返回</a></li>
</ul>
<?php
	$cnt=0;
	$filenames=scandir("/var/www/uoj/imgupload/");
?>
<form class="form-horizontal" action="" method="post" name="remover" role="form">
	<div class="form-group">
		<div class="row">
    			<?php
					for($i=2;$i<count($filenames);$i++){
						$image = $filenames[$i];
						if(strstr($image,"problem_".$problem['id']."_")){
							$cnt++;
							?>
							<div class="col-sm-6 col-md-4">
    							<div class="thumbnail">
      								<img  data-src="holder.js/300x300" src="/imgupload/<?php echo $image; ?>">
      								<div class="caption">
        								<input type="checkbox" name="rm[]" value='.'"'.$image.'"'.'>选择
      								</div>
    							</div>
  							</div>
				<?php
						}
					}
    			?>
    	</div>
		<?php
			if($cnt==0){
				echo '<h4 align="center">这道题里并没有用到什么图片...</h4>';
			}else{
				echo '<input type="hidden" name="Removal" value="Removal">
					<div align="center"><button class="btn btn-danger" type="submit">确认删除</button></div>';
			}
		?>
		
	</div>
</form>

<?php echoUOJPageFooter() ?>