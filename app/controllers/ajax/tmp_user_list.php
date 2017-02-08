<?php
	if ($myUser == null || !isSuperUser($myUser)) {
		become403Page();
	}

	if(isset($_GET['group'])){
		$group = DB::escape($_GET['group']);
		$group = htmlspecialchars($group);
		$cond = "group_name like '%".$group."'%";
	}else{
		$cond = "1";
	}

	$page_cnt = DB::selectCount("select count(*) from tmp_users where ".$cond.";");
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

<table class="table table-hover table-striped table-text-center">
	<thead>
		<tr>
			<th>#</th>
			<th>临时用户组名</th>
			<th>人数</th>
			<th>操作</th>
		</tr>
	</thead>
	<tbody>
		<?php
			if($page_cnt<=0){
				echo '<tr><td colspan="233">暂无用户组</td></tr>';
			}else{
				$sql = mysql_query("select * from tmp_users where ".$cond." order by id asc;");
				while($info = mysql_fetch_array($sql)){
					$cnt++;
		?>

		<tr>
			<td><?php echo $cnt; ?></td>
			<td><?php echo $info['user_prefix']; ?></td>
			<td><?php echo DB::selectCount("select count(*) from user_info where username like '%".$info['user_prefix']."';");; ?></td>
			<td><a href="javascript:managegroup(<?php echo $info['id']; ?>)">管理用户组</a> <a href="javascript:deletegroup(<?php echo $info['id'].', \''.$info['user_prefix'].'\''; ?>)">删除</a></td>
		</tr>

		<?php
				}
			}
		?>

	</tbody>
</table>

