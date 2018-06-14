<?php
	if ($myUser == null || !isSuperUser($myUser)) {
		become403Page();
	}

	if(isset($_GET['group'])){
		$group = DB::escape($_GET['group']);
		$group = htmlspecialchars($group);
		$cond = "group_id = ".$group.";";
	}else{
		$cond = "1";
	}

?>

<div>
	<ul class="nav nav-tabs" role="tablist">
		<li role="presentation" class="active"><a href="#groupuser" role="tab" data-toggle="tab">成员管理</a></li>
		<!--<li role="presentation"><a href="#groupnuser" role="tab" data-toggle="tab">添加用户</a></li>-->
		<li role="presentation"><a href="#grouppermission" role="tab" data-toggle="tab">权限管理</a></li>
	</ul>

	<div class="tab-content">
		<!--<div role="tabpanel" class="tab-pane fade in active" id="groupuser">
			<div id="usergroupuserlist"></div>
		</div>

		<div role="tabpanel" class="tab-pane fade" id="groupnuser">
			<div id="usergroupnuserlist"></div>
		</div>-->
		
		<div role="tabpanel" class="tab-pane fade in active" id="groupuser">
			<div class="row">
				<div class="col-xs-12 col-md-6">
					<div id="usergroupuserlist"></div>
				</div>
				<div class="col-xs-12 col-md-6">
					<div id="usergroupnuserlist"></div>
				</div>
			</div>
		</div>

		<div role="tabpanel" class="tab-pane fade" id="grouppermission">

		</div>
	</div>
</div>


<script>
	function getuserlist(groupid, pageid){
		$.get("/ajax/user-group-user-list?group=" + groupid + "&page=" + pageid, function(data){
			$('#usergroupuserlist').html(data);
		});
	}

	function getnuserlist(groupid, pageid){
		$.get("/ajax/user-group-nuser-list?group=" + groupid + "&page=" + pageid, function(data){
			$('#usergroupnuserlist').html(data);
		});
	}
	getuserlist(<?php echo $group; ?>);
	getnuserlist(<?php echo $group; ?>);
</script>
