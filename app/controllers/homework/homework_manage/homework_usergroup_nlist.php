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
	
	$groups = $info['belong'];
	
	$page_cnt = 1;
	
	$groups = ",".$groups;
	
	$sql = mysql_query("select * from user_group;");
	
	$cnt = 0;
?>

<table class="table table-hover table-striped table-text-center">
	<thead>
		<tr>
			<th>#</th>
			<th>组名</th>
			<th>操作</th>
		</tr>
	</thead>
	<tbody>
		<?php
			if($page_cnt<=0){
				echo '<tr><td colspan="233">暂无用户组</td></tr>';
			}else{
				while($info = mysql_fetch_array($sql)){
					if(strpos($groups, ",".$info['id'].",") !== false){
						continue;
					}
					$cnt++;
		?>

		<tr>
			<td><?php echo $cnt; ?></td>
			<td><?php echo $info['group_name']; ?></td>
			<td><a href="javascript:addhomeworkusergroup(<?php echo $info['id']; ?>)">添加</a></td>
		</tr>

		<?php
				}
			}
		?>

	</tbody>
</table>
