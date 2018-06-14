<?php
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
	
	$blog_editor = new UOJBlogEditor();
	$blog_editor->name = 'blog';
	if ($blog) {
		$blog_editor->cur_data = array(
			'title' => $blog['title'],
			'content_md' => $blog['content_md'],
			'content' => $blog['content'],
			'tags' => queryBlogTags($blog['id']),
			'is_hidden' => $blog['is_hidden']
		);
	} else {
		$blog_editor->cur_data = array(
			'title' => '新博客',
			'content_md' => '',
			'content' => '',
			'tags' => array(),
			'is_hidden' => true
		);
	}
	if ($blog && !$blog['is_draft']) {
		$blog_editor->blog_url = "/blog/{$blog['id']}";
	} else {
		$blog_editor->blog_url = null;
	}
	
	function updateBlog($id, $data) {
		DB::update("update blogs set title = '".DB::escape($data['title'])."', content = '".DB::escape($data['content'])."', content_md = '".DB::escape($data['content_md'])."', is_hidden = {$data['is_hidden']} where id = {$id}");
	}
	function insertBlog($data) {
		DB::insert("insert into blogs (title, content, content_md, poster, is_hidden, is_draft, post_time) values ('".DB::escape($data['title'])."', '".DB::escape($data['content'])."', '".DB::escape($data['content_md'])."', '".Auth::id()."', {$data['is_hidden']}, {$data['is_draft']}, now())");
	}
	
	$blog_editor->save = function($data) {
		global $blog;
		$ret = array();
		if ($blog) {
			if ($blog['is_draft']) {
				if ($data['is_hidden']) {
					updateBlog($blog['id'], $data);
				} else {
					deleteBlog($blog['id']);
					insertBlog(array_merge($data, array('is_draft' => 0)));
					$blog = array('id' => DB::insert_id(), 'tags' => array());
					$ret['blog_write_url'] = "/blog/{$blog['id']}/write";
					$ret['blog_url'] = "/blog/{$blog['id']}";
				}
			} else {
				updateBlog($blog['id'], $data);
			}
		} else {
			$blog = array('id' => DB::insert_id(), 'tags' => array());
			if ($data['is_hidden']) {
				insertBlog(array_merge($data, array('is_draft' => 1)));
			} else {
				insertBlog(array_merge($data, array('is_draft' => 0)));
				$ret['blog_write_url'] = "/blog/{$blog['id']}/write";
				$ret['blog_url'] = "/blog/{$blog['id']}";
			}
		}
		if ($data['tags'] !== $blog['tags']) {
			DB::delete("delete from blogs_tags where blog_id = {$blog['id']}");
			foreach ($data['tags'] as $tag) {
				DB::insert("insert into blogs_tags (blog_id, tag) values ({$blog['id']}, '".DB::escape($tag)."')");
			}
		}
		return $ret;
	};
	
	$blog_editor->runAtServer();
?>
<?php echoUOJPageHeader('写博客') ?>
<div class="text-right">
<a href="http://uoj.ac/blog/7">这玩意儿怎么用？</a>
</div>
<?php $blog_editor->printHTML() ?>

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
	var imgupload_url="/blog/imgupload";
	$.ajax({
    	url: imgupload_url,
    	type: 'POST',
    	cache: false,
    	data: new FormData($('#imguploadform')[0]),
    	processData: false,
    	contentType: false
	}).success(function(res) {
		if(res.substr(0,2) == "ok"){
			alert("上传成功！");
			$('#UploadImgModal').modal('hide');
			window.codee.focus();
			addcode="![]("+ res.substring(3) +")";
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

<?php //dhxh end?>

<?php echoUOJPageFooter() ?>
