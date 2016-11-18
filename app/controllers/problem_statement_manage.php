<?php
	requirePHPLib('form');
	
	if (!validateUInt($_GET['id']) || !($problem = queryProblemBrief($_GET['id']))) {
		become404Page();
	}
	if (!hasProblemPermission($myUser, $problem)) {
		become403Page();
	}
	
	$problem_content = queryProblemContent($problem['id']);
	$problem_tags = queryProblemTags($problem['id']);
	
	$problem_editor = new UOJBlogEditor();
	$problem_editor->name = 'problem';
	$problem_editor->blog_url = "/problem/{$problem['id']}";
	$problem_editor->cur_data = array(
		'title' => $problem['title'],
		'content_md' => $problem_content['statement_md'],
		'content' => $problem_content['statement'],
		'tags' => $problem_tags,
		'is_hidden' => $problem['is_hidden']
	);
	$problem_editor->label_text = array_merge($problem_editor->label_text, array(
		'view blog' => '查看题目',
		'blog visibility' => '题目可见性'
	));
	
	$problem_editor->save = function($data) {
		global $problem, $problem_tags;
		DB::update("update problems set title = '".DB::escape($data['title'])."' where id = {$problem['id']}");
		DB::update("update problems_contents set statement = '".DB::escape($data['content'])."', statement_md = '".DB::escape($data['content_md'])."' where id = {$problem['id']}");
		
		if ($data['tags'] !== $problem_tags) {
			DB::delete("delete from problems_tags where problem_id = {$problem['id']}");
			foreach ($data['tags'] as $tag) {
				DB::insert("insert into problems_tags (problem_id, tag) values ({$problem['id']}, '".DB::escape($tag)."')");
			}
		}
		if ($data['is_hidden'] != $problem['is_hidden'] ) {
			DB::update("update problems set is_hidden = {$data['is_hidden']} where id = {$problem['id']}");
			DB::update("update submissions set is_hidden = {$data['is_hidden']} where problem_id = {$problem['id']}");
			DB::update("update hacks set is_hidden = {$data['is_hidden']} where problem_id = {$problem['id']}");
		}
	};
	
	$problem_editor->runAtServer();
?>
<?php echoUOJPageHeader(HTML::stripTags($problem['title']) . ' - 编辑 - 题目管理') ?>
<h1 class="page-header" align="center">#<?=$problem['id']?> : <?=$problem['title']?> 管理</h1>
<ul class="nav nav-tabs" role="tablist">
	<li class="active"><a href="/problem/<?= $problem['id'] ?>/manage/statement" role="tab">编辑</a></li>
	<li><a href="/problem/<?= $problem['id'] ?>/manage/managers" role="tab">管理者</a></li>
	<li><a href="/problem/<?= $problem['id'] ?>/manage/data" role="tab">数据</a></li>
	<?php //dhxh begin ?>
	<!--<li><a href="/problem/<?= $problem['id'] ?>/manage/imgupload" role="tab">图片上传</a></li>-->
	<?php //dhxh end ?>
	<?php// AD1024 Modify on 2016.10.20 ?>
	<li><a href="/problem/<?= $problem['id']?>/manage/imgmanage" role="tab">图片管理</a></li>
	<?php// AD1024 Modify on 2016.10.20 ?>
	<li><a href="/problem/<?=$problem['id']?>" role="tab">返回</a></li>
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
	var imgupload_url="/problem/<?= $problem['id'] ?>/manage/imgupload";
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
