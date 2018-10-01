<?php
	requirePHPLib('form');
	requirePHPLib('judger');
	if ($myUser == null || !isSuperUser($myUser)) {
		become403Page();
	}
	
	$REQUIRE_LIB['dtp'] = '';
	$REQUIRE_LIB['shjs'] = "";
	
	echoUOJPageHeader("作业管理");
?>

<div class="row">
	<div align="center"><h1>作业管理</h1></div>
	<div align="right"><button type="button" class="btn btn-success" data-toggle="modal" data-target="#AddHomeworkModal">添加新作业</button></div>
	
	<div class="col-md-6">
		<div align="center"><h3>作业列表</h3></div>
		<div id="homeworklist"></div>
	</div>
	
	<div class="col-md-6">
		<div align="center"><h3>作业管理</h3></div>
		<div id="homeworkdetails"></div>
	</div>

</div>

<div class="modal fade" id="AddHomeworkModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel">添加作业</h4>
			</div>
			<div class="modal-body">
				<form class="form-horizontal" id="addhomework" role="form">
					<div class="form-group">
						<label for="addhomework_title" class="col-sm-3 control-label">标题：</label>
						<div class="col-sm-9">
							<input type="text" class="form-control" id="addhomework_title" placeholder="标题">
						</div>
					</div>
					<div class="form-group">
						<label for="addhomework_content" class="col-sm-3 control-label">内容：</label>
						<div class="col-sm-9">
							<textarea class="form-control" id="addhomework_content" placeholder="内容" rows="3"></textarea>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal">关闭</button>
				<button type="button" id="addhomework_submit" class="btn btn-success">添加</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="HomeworkResultModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" style="width:1050px">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel">作业完成情况</h4>
			</div>
			<div class="modal-body">
				<div id="homeworkresult">
					
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal">关闭</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="MsgModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel">提示</h4>
			</div>
			<div id="msg_content" class="modal-body"></div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success" data-dismiss="modal">确定</button>
			</div>
		</div>
	</div>
</div>

<script language="javascript">

	$('#addhomework').submit(function() {
		$("#addhomework_submit").click();
		return false;
	});

	$("#addhomework_submit").click(function() {
		var $btn = $(this).button('loading');
		$.ajax({
			type: "GET",
			url: "/homework/manage/add",
			data: { title: addhomework_title.value, content: addhomework_content.value }
		}).done(function( msg ) {
			if(msg == "OK"){
				addhomework_title.value = "";
				addhomework_content.value = "";
				$('#AddHomeworkModal').modal('hide');
				msg_content.innerHTML = "<p class='text-success'>添加成功！</p>";
				reflash(1);
				$('#MsgModal').modal('show');
			}else{
				alert(msg);
			}
		});
		$btn.button('reset');
	});

	function managehomework(homeworkid){
		$.get("/homework/manage/details?id=" + homeworkid, function(data){
			$('#homeworkdetails').html(data);
		});
	}
	
	function deletehomework(homeworkid, title){
		$.ajax({
			type: "GET",
			url: "/homework/manage/delete",
			data: { id: homeworkid }
		}).done(function( msg ) {
			if(msg == "OK"){
				msg_content.innerHTML = "<p class='text-success'>删除成功！</p>";
				reflash(1);
				$('#MsgModal').modal('show');
			}else{
				alert(msg);
			}
		});
	}
	
	function homeworkresult(homeworkid){
		$.get("/homework/manage/result?id=" + homeworkid, function(data){
			$('#homeworkresult').html(data);
			$('#HomeworkResultModal').modal('show');
		});
	}

	function reflash(page_id){
		$.get("/homework/manage/list?page=" + page_id, function(data){
			$('#homeworklist').html(data);
		});
	}
	
	reflash(1);
</script>

<?php echoUOJPageFooter();?>
