<?php
	requirePHPLib('form');
	requirePHPLib('judger');
	if ($myUser == null) {
		become404Page();
	}
	
	$REQUIRE_LIB['dtp'] = '';
	
	echoUOJPageHeader("作业");
?>

<div class="row">
	<div align="center"><h1>作业</h1></div>
	
	<div class="col-md-6">
		<div align="center"><h3>作业列表</h3></div>
		<div id="homeworklist"></div>
	</div>
	
	<div class="col-md-6">
		<div align="center"><h3>作业内容</h3></div>
		<div id="homeworkdetails"></div>
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

	function reflash(page_id){
		$.get("/homework/list?page=" + page_id, function(data){
			$('#homeworklist').html(data);
		});
	}
	
	function homeworkdetail(homework_id){
		$.get("/homework/detail?id=" + homework_id, function(data){
			$('#homeworkdetails').html(data);
		});
	}
	
	function homeworkresult(homeworkid){
		$.get("/homework/result?id=" + homeworkid, function(data){
			$('#homeworkresult').html(data);
			$('#HomeworkResultModal').modal('show');
		});
	}
	
	reflash(1);
</script>

<?php echoUOJPageFooter();?>
