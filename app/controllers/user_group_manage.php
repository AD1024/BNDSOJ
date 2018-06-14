<?php
	requirePHPLib('form');
	requirePHPLib('judger');
	
	if ($myUser == null || !isSuperUser($myUser)) {
		become403Page();
	}

	$cur_tab = 'user-group';
	
	$tabs_info = array(
		'users' => array(
			'name' => '用户操作',
			'url' => "/super-manage/users"
		),
		'blogs' => array(
			'name' => '博客管理',
			'url' => "/super-manage/blogs"
		),
		'submissions' => array(
			'name' => '提交记录',
			'url' => "/super-manage/submissions"
		),
		'custom-test' => array(
			'name' => '自定义测试',
			'url' => '/super-manage/custom-test'
		),
		'click-zan' => array(
			'name' => '点赞管理',
			'url' => '/super-manage/click-zan'
		),
		'search' => array(
			'name' => '搜索管理',
			'url' => '/super-manage/search'
		),
		'user-group' => array(
			'name' => '用户组管理',
			'url' => '/super-manage/user-group'
		),
		'tmp-user' => array(
			'name' => '临时用户管理',
			'url' => '/super-manage/tmp-user'
		),
		'user-password-reset' => array(
			'name' => '用户密码重置',
			'url' => '/super-manage/user-password-reset'
		),
		'register' => array(
			'name' => '注册开关',
			'url' => '/super-manage/register'
		)
	);

?>
<?php echoUOJPageHeader('系统管理') ?>
<div class="row">
	<div class="col-sm-3">
		<?= HTML::tablist($tabs_info, $cur_tab, 'nav-pills nav-stacked') ?>
	</div>
	
	<div class="col-sm-9">
		<div align="center"><h1>用户组管理</h1></div>
		<div class="col-sm-4">
			<button type="button" class="btn btn-danger disabled" data-toggle="modal" data-target="#DeleteGroupModal">删除已选用户组</button>
		</div>
		<div class="col-sm-4"></div>
		<div class="col-sm-4" align="right">
			<button type="button" class="btn btn-success" data-toggle="modal" data-target="#AddGroupModal">添加用户组</button>
		</div>
		<div id="user_group_list">
			<?php include "ajax/user_group_list.php"; ?>
		</div>
	</div>
</div>


<div class="modal fade" id="AddGroupModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel">添加用户组</h4>
			</div>
			<div class="modal-body">
				<form class="form-horizontal" id="addgroup" role="form">
					<div class="form-group">
						<label for="addgroup_groupname" class="col-sm-3 control-label">组名:</label>
						<div class="col-sm-9">
							<input type="text" class="form-control" id="addgroup_groupname" placeholder="组名">
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal">关闭</button>
				<button type="button" id="addgroup_submit" class="btn btn-success">添加</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="DeleteGroupModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel">删除用户组</h4>
			</div>
			<div class="modal-body">
				<b>你确定要删除以下用户组么：</b>
				<div id="deletegroups"></div>
				<input type="hidden" id='daletegroupids'>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" data-dismiss="modal">取消</button>
				<button type="button" id="deletegroup_submit" class="btn btn-danger">删除</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="GroupManageModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel">组管理</h4>
			</div>
			<div id="group_manage_content" class="modal-body"></div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success" data-dismiss="modal">完成</button>
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

	$('#addgroup').submit(function() {
		$("#addgroup_submit").click();
		return false;
	});

	$("#addgroup_submit").click(function() {
		var $btn = $(this).button('loading');
		$.ajax({
			type: "GET",
			url: "/api/manage/user/group",
			data: { op: "0", group: addgroup_groupname.value }
		}).done(function( msg ) {
			if(msg == "OK"){
				addgroup_groupname.value = "";
				$('#AddGroupModal').modal('hide');
				msg_content.innerHTML = "<p class='text-success'>添加成功！</p>";
				reflash(1);
				$('#MsgModal').modal('show');
			}else{
				alert(msg);
			}
		});
		$btn.button('reset');
	});

	$("#deletegroup_submit").click(function() {
		var $btn = $(this).button('loading');
		$.ajax({
			type: "GET",
			url: "/api/manage/user/group",
			data: { op: "4", group: daletegroupids.value }
		}).done(function( msg ) {
			if(msg == "OK"){
				addgroup_groupname.value = "";
				$('#DeleteGroupModal').modal('hide');
				msg_content.innerHTML = "<p class='text-success'>删除成功！</p>";
				reflash(1);
				$('#MsgModal').modal('show');
			}else{
				alert(msg);
			}
		});
		$btn.button('reset');
	});

	function managegroup(groupid){
		url = "/ajax/user-group-manage?group=" + groupid;
		$.get(url, function(data){
			$('#group_manage_content').html(data);
		}); 
		//getuserlist(groupid);
		//getnuserlist(groupid);
		$('#GroupManageModal').modal('show');
	}

	function deletegroup(groupid, groupname){
		deletegroups.innerHTML = "<p class='text-danger'>" + groupname + "</p>";
		daletegroupids.value = groupid;
		$('#DeleteGroupModal').modal('show');
	}

	function reflash(page_id){
		$.get("/ajax/user-group-list?page=" + page_id, function(data){
			$('#user_group_list').html(data);
		});
	}

	

</script>


<?php echoUOJPageFooter() ?>
