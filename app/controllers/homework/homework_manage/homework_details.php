<?php
	if ($myUser == null || !isSuperUser($myUser)) {
		become403Page();
	}

	if(!validateUInt($_GET['id'])){
		become403Page();
	}else{
		$homework_id = DB::escape($_GET['id']);
	}
	
	$sql = mysql_query("select * from homework_index where id = ".$homework_id.";");
	$info = mysql_fetch_array($sql);

?>

<div>
	<ul class="nav nav-tabs" role="tablist">
		<li role="presentation" class="active"><a href="#homework_manage_content" role="tab" data-toggle="tab">内容</a></li>
		<li role="presentation"><a href="#homework_manage_problems" role="tab" data-toggle="tab">添加试题</a></li>
		<li role="presentation"><a href="#homework_manage_groups" role="tab" data-toggle="tab">添加用户组</a></li>
	</ul>

	<div class="tab-content">
		
		<div role="tabpanel" class="tab-pane fade in active" id="homework_manage_content">
			<br>
			<form class="form-horizontal" id="homeworkupdate" role="form">
				<div class="form-group">
					<label for="homeworkupdate_title" class="col-sm-3 control-label">标题：</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" id="homeworkupdate_title" placeholder="标题" value="<?php echo $info['title']; ?>">
					</div>
				</div>
				<div class="form-group">
					<label for="homeworkupdate_content" class="col-sm-3 control-label">内容：</label>
					<div class="col-sm-9">
						<textarea class="form-control" id="homeworkupdate_content" placeholder="内容" rows="3"><?php echo $info['msg']; ?></textarea>
					</div>
				</div>
				<div class="form-group">
					<label for="homeworkupdate_duedate" class="col-md-3 control-label">结束日期：</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" id="homeworkupdate_duedate" value="<?php echo $info['due']; ?>">
					</div>
				</div>
				<div class="form-group">
					<div align="center"><button type="button" class="btn btn-success" id="homeworkupdate_submit">更改</button></div>
				</div>
			</form>
		</div>
		
		<div role="tabpanel" class="tab-pane fade" id="homework_manage_problems">
			<div>
				<br>
				<form class="form-horizontal" id="homeworkadd_problem" role="form">
					<div class="form-group">
						<label for="homeworkupdate_title" class="col-sm-3 control-label">试题编号：</label>
						<div class="col-sm-9">
							<input type="text" class="form-control" id="homeworkadd_problem_id" placeholder="试题编号">
						</div>
					</div>
					<div class="form-group">
						<div align="center"><button type="button" class="btn btn-success" id="homeworkadd_problem_submit">添加</button></div>
					</div>
				</form>
			</div>
			
			
			<div id="homeworkproblemlist">
				
			</div>
		</div>

		<div role="tabpanel" class="tab-pane fade" id="homework_manage_groups">
			<div class="col-md-6" id="homeworkgrouplist">
				
			</div>
			<div class="col-md-6" id="homeworkgroupnlist">
				
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$('.form_datetime').datetimepicker({
		language:  'zh-CN',
		weekStart: 1,
		todayBtn:  1,
		autoclose: 1,
		todayHighlight: 1,
		startView: 2,
		forceParse: 0,
		showMeridian: 1
	});
	$("#homeworkupdate_duedate").datetimepicker({format: 'yyyy-mm-dd hh:ii'});
</script>

<script>
	
	$('#homeworkupdate').submit(function() {
		$("#homeworkupdate_submit").click();
		return false;
	});
	
	$("#homeworkupdate_submit").click(function() {
		var $btn = $(this).button('loading');
		$.ajax({
			type: "GET",
			url: "/homework/manage/edit",
			data: { op:1, id: <?php echo $_GET['id']; ?>, title: homeworkupdate_title.value, content: homeworkupdate_content.value, duetime: homeworkupdate_duedate.value }
		}).done(function( msg ) {
			if(msg == "OK"){
				msg_content.innerHTML = "<p class='text-success'>修改成功！</p>";
				$('#MsgModal').modal('show');
				reflashdetail(<?php echo $homework_id; ?>);
				reflash(1);
			}else{
				alert(msg);
			}
		});
		$btn.button('reset');
	});
	
	$('#homeworkadd_problem').submit(function() {
		$("#homeworkadd_problem_submit").click();
		return false;
	});
	
	$("#homeworkadd_problem_submit").click(function() {
		var $btn = $(this).button('loading');
		$.ajax({
			type: "GET",
			url: "/homework/manage/edit",
			data: { op:2, id: <?php echo $_GET['id']; ?>, problem_id: homeworkadd_problem_id.value }
		}).done(function( msg ) {
			if(msg == "OK"){
				homeworkadd_problem_id.value = "";
				reflashproblemlist(<?php echo $homework_id; ?>);
			}else{
				alert(msg);
			}
		});
		$btn.button('reset');
	});
	
	function deletehomeworkproblem(homework_problem_id){
		$.ajax({
			type: "GET",
			url: "/homework/manage/edit",
			data: { op:3, id: <?php echo $_GET['id']; ?>, problem_id: homework_problem_id }
		}).done(function( msg ) {
			if(msg == "OK"){
				homeworkadd_problem_id.value = "";
				reflashproblemlist(<?php echo $homework_id; ?>);
			}else{
				alert(msg);
			}
		});
	}
	
	function addhomeworkusergroup(homework_group_id){
		$.ajax({
			type: "GET",
			url: "/homework/manage/edit",
			data: { op:4, id: <?php echo $_GET['id']; ?>, group_id: homework_group_id }
		}).done(function( msg ) {
			if(msg == "OK"){
				homeworkadd_problem_id.value = "";
				reflashusergrouplist(<?php echo $homework_id; ?>);
				reflashusergroupnlist(<?php echo $homework_id; ?>);
			}else{
				alert(msg);
			}
		});
	}
	
	function deletehomeworkusergroup(homework_group_id){
		$.ajax({
			type: "GET",
			url: "/homework/manage/edit",
			data: { op:5, id: <?php echo $_GET['id']; ?>, group_id: homework_group_id }
		}).done(function( msg ) {
			if(msg == "OK"){
				homeworkadd_problem_id.value = "";
				reflashusergrouplist(<?php echo $homework_id; ?>);
				reflashusergroupnlist(<?php echo $homework_id; ?>);
			}else{
				alert(msg);
			}
		});
	}
	
	function reflashdetail(homeworkid){
		$.get("/homework/manage/details?id=" + homeworkid, function(data){
			$('#homeworkdetails').html(data);
		});
	}
	
	function reflashproblemlist(homeworkid){
		$.get("/homework/manage/details/problemlist?id=" + homeworkid, function(data){
			$('#homeworkproblemlist').html(data);
		});
	}
	
	function reflashusergrouplist(homeworkid){
		$.get("/homework/manage/details/usergrouplist?id=" + homeworkid, function(data){
			$('#homeworkgrouplist').html(data);
		});
	}
	
	function reflashusergroupnlist(homeworkid){
		$.get("/homework/manage/details/usergroupnlist?id=" + homeworkid, function(data){
			$('#homeworkgroupnlist').html(data);
		});
	}
	
	reflashproblemlist(<?php echo $homework_id ?>);
	reflashusergrouplist(<?php echo $homework_id ?>);
	reflashusergroupnlist(<?php echo $homework_id ?>);
	
</script>
