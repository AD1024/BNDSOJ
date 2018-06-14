<?php
	requirePHPLib('form');
	
	if (!validateUInt($_GET['id'])) {
		become404Page();
	}
	if ($myUser == null) {
		redirectToLogin();
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
	
	$problem_editor = new UOJBlogEditor();
	$problem_editor->name = 'material';
	$problem_editor->blog_url = "/material/{$material['id']}";
	$problem_editor->cur_data = array(
		'title' => $material['title'],
		'content_md' => $material_content['statement_md'],
		'content' => $material_content['statement'],
		'tags' => $material_tags,
		'is_hidden' => $material['is_hidden']
	);
	$problem_editor->label_text = array_merge($problem_editor->label_text, array(
		'view blog' => '查看资料',
		'blog visibility' => '资料可见性'
	));
	
	$problem_editor->save = function($data) {
		global $material;
		DB::update("update materials set title = '".DB::escape($data['title'])."' where id = {$material['id']}");
		DB::update("update materials_contents set statement = '".DB::escape($data['content'])."', statement_md = '".DB::escape($data['content_md'])."' where id = {$material['id']}");

		if ($data['is_hidden'] != $material['is_hidden'] ) {
			DB::update("update materials set is_hidden = {$data['is_hidden']} where id = {$material['id']}");
		}
	};
	
	$problem_editor->runAtServer();
?>
<?php echoUOJPageHeader(HTML::stripTags($material['title']) . ' - 编辑 - 题目管理') ?>
<h1 class="page-header" align="center">#<?=$material['id']?> : <?=$material['title']?> 管理</h1>
<ul class="nav nav-tabs" role="tablist">
	<li class="active"><a href="/material/<?= $material['id'] ?>/manage/statement" role="tab">编辑</a></li>
	<li><a href="/material/<?= $material['id']?>/manage/imgmanage" role="tab">图片管理</a></li>
	<li><a href="/material/<?=$material['id']?>" role="tab">返回</a></li>
</ul>

<?php //dhxh begin ?>
<script>window.codee=CodeMirror();</script>
<?php //dhxh end ?>

<?php $problem_editor->printHTML() ?>

<?php //dhxh begin ?>

<div class="modal fade" id="UploadImgModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
    			<h4 class="modal-title" id="myModalLabel">上传数据</h4>
			</div>
			<div class="modal-body">
				<form id="imguploadform" action="" method="post" enctype="multipart/form-data" role="form">
					<div class="form-group">
						<label for="exampleInputFile">文件</label>
						<input type="file" name="problem_img_file" id="problem_img_file">
						<p class="help-block">请上传.jpg/.jpeg/.gif/.png文件</p>
					</div>
					<input type="hidden" name="problem_img_file_submit" value="submit">
					<button id="uploadimgbtn" type="submit" class="btn btn-success">上传</button>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
			</div>
		</div>
	</div>
</div>

<script language="javascript">

function add_around(sl, sr) {
	window.codee.replaceSelection(sl + window.codee.getSelection() + sr);
}

function imgupload(){
	var imgupload_url="/material/<?= $material['id'] ?>/manage/imgupload";
	$.ajax({
    	url: imgupload_url,
    	type: 'POST',
    	cache: false,
    	data: new FormData($('#imguploadform')[0]),
    	processData: false,
    	contentType: false
	}).success(function(res) {
		if(res.substr(1,2)=="ok"){
			alert("上传成功！");
			$('#UploadImgModal').modal('hide');
			window.codee.focus();
			addcode="![]("+ res.substring(4) +")";
			add_around("", addcode);
		}else{
			alert(res);
		}
			
	}).fail(function(res) {
		alert("上传失败，请重新上传！");
	});
	return false;
}

$("#uploadimgbtn").click(function() {
	window.codee.focus();
	imgupload();
	return false;
});
	
</script>

<?php //dhxh end ?>

<?php echoUOJPageFooter() ?>
