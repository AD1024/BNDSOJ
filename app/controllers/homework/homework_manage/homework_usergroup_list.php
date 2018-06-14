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
	
	$problems = explode(",", $info['belong']);
	
	$page_cnt = 0;
	
	foreach($problems as $x){
		if(validateUInt($x)){
			$page_cnt = 1;
			break;
		}
	}
	
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
				foreach($problems as $x){
					if(!validateUInt($x)){
						continue;
					}
					$sql = mysql_query("select * from user_group where id = ".$x.";");
					$info = mysql_fetch_array($sql);
					$cnt++;
		?>

		<tr>
			<td><?php echo $cnt; ?></td>
			<td><?php echo $info['group_name']; ?></td>
			<td><a href="javascript:deletehomeworkusergroup(<?php echo $info['id']; ?>)">删除</a></td>
		</tr>

		<?php
				}
			}
		?>

	</tbody>
</table>
