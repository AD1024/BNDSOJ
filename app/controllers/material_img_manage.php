<?php
/*
* Created by AD1024
* Modified by dhxh
* */
	requirePHPLib('form');
	if (!validateUInt($_GET['id'])) {
		become404Page();
	}
	if(DB::selectCount("select * from materials where id=".$_GET['id'].";") == 0){
		become404Page();
	}

	$material = mysql_fetch_array(mysql_query("select * from materials where id=".$_GET['id'].";"), MYSQL_ASSOC);

	if(!isSuperUser($myUser)){
		become404Page();
	}

	//dhxh begin
	function queryMaterials($id) {
		return mysql_fetch_array(mysql_query("select * from materials_contents where id='$id'"), MYSQL_ASSOC);
	}
	
	$material_content = queryMaterials($material['id']);
	if(isset($_POST["Removal"])){
		$filedir="/var/www/uoj/imgupload/";
		foreach ($_REQUEST['rm'] as $del) {
			// Delete certain file
			//过滤危险字符
			$del=preg_replace("#/#", "", $del);
			unlink($filedir.$del);
		}
		echo '<script>alert("删除成功！")</script>';
	}

?>

<?php echoUOJPageHeader(HTML::stripTags($material['title']) . ' - 编辑 - 题目管理') ?>
<h1 class="page-header" align="center">#<?=$material['id']?> : <?=$material['title']?> 管理</h1>
<ul class="nav nav-tabs" role="tablist">
	<li><a href="/material/<?= $material['id'] ?>/manage/statement" role="tab">编辑</a></li>
	<li class="active"><a href="/material/<?= $material['id']?>/manage/imgmanage" role="tab">图片管理</a></li>
	<li><a href="/material/<?=$material['id']?>" role="tab">返回</a></li>
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
						if(strstr($image,"material_".$material['id']."_")){
							$cnt++;
							?>
							<div class="col-sm-6 col-md-4">
    							<div class="thumbnail">
      								<img  data-src="holder.js/300x300" src="/imgupload/<?php echo $image; ?>">
      								<div class="caption">
      									<p>图片地址：<?php echo "/imgupload/".$image; ?></p>
        								<input type="checkbox" name="rm[]" value="<?php echo $image; ?>">选择
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
				echo '<h4 align="center">这里并没有用到什么图片...</h4>';
			}else{
				echo '<input type="hidden" name="Removal" value="Removal">
					<div align="center"><button class="btn btn-danger" type="submit">确认删除</button></div>';
			}
		?>
		
	</div>
</form>

<?php echoUOJPageFooter() ?>