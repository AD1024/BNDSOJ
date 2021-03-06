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

	$cond = "!(select count(*) from user_group_map where user_info.username = user_group_map.username and group_id = ".$group.")";

	$page_cnt = DB::selectCount("select count(*) from user_info where ".$cond.";");
	$page_cnt = floor(($page_cnt-1) / 10) + 1;

	if(!validateUInt($_GET['page']) or $_GET['page'] <= 0){
		$page = 1;
	}else{
		$page = $_GET['page'];
	}

	if($page > $page_cnt){
		$page = $page_cnt;
	}

	$cnt = 0;
?>

<?= HTML::js_src('/js/uoj.js?v=2016.8.15') ?>

<table class="table table-hover table-striped table-text-center">
	<thead>
		<tr>
			<th>#</th>
			<th>用户名</th>
			<th>操作</th>
		</tr>
	</thead>
	<tbody>
		<?php
			if($page_cnt<=0){
				echo '<tr><td colspan="233">暂无用户</td></tr>';
			}else{
				$sql = mysql_query("select * from user_info where ".$cond." order by username asc;");
				while($info = mysql_fetch_array($sql)){
					$uhtml = getUserLink($info['username']);

					$cnt++;
		?>

		<tr>
			<td><?php echo $cnt; ?></td>
			<td><?php echo $uhtml; ?></td>
			<td><a href="javascript:addusertogroup(<?php echo "'".$info['username']."', ".$group; ?>);">添加</a></td>
		</tr>

		<?php
				}
			}
		?>

	</tbody>
</table>

<script>
	function addusertogroup(username, groupid){
		$.ajax({
			type: "GET",
			url: "/api/manage/user/group",
			data: { op: "2", group: groupid, users: username }
		}).done(function( msg ) {
			if(msg == "OK"){
				getuserlist(<?php echo $group; ?>);
				getnuserlist(<?php echo $group; ?>);
				reflash();
			}else{
				alert(msg);
			}
		});
	}

</script>
