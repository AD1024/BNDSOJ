<?php
	if ($myUser == null || !isSuperUser($myUser)) {
		become403Page();
	}
	
	$cond = "1";

	$page_cnt = DB::selectCount("select count(*) from homework_index where ".$cond.";");
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
			<th>标题</th>
			<th>操作</th>
		</tr>
	</thead>
	<tbody>
		<?php
			if($page_cnt<=0){
				echo '<tr><td colspan="233">暂无作业</td></tr>';
			}else{
				$sql = mysql_query("select * from homework_index where ".$cond." order by id desc;");
				while($info = mysql_fetch_array($sql)){
					$cnt++;
					if($cnt <= ($page-1) * 10){
						continue;
					}

					if($cnt > $page * 10){
						break;
					}
		?>

		<tr>
			<td><?php echo $cnt; ?></td>
			<td><?php echo $info['title']; ?></td>
			<td><a href="javascript:homeworkresult(<?php echo $info['id']; ?>)">完成情况</a> <a href="javascript:managehomework(<?php echo $info['id']; ?>)">管理</a> <a href="javascript:deletehomework(<?php echo $info['id'].', \''.$info['title'].'\''; ?>)">删除</a></td>
		</tr>

		<?php
				}
			}
		?>

	</tbody>
</table>

<div class="text-center">
	<ul class="pagination top-buffer-no bot-buffer-sm">
		<li<?php if($page==1)echo ' class="disabled"'; ?>>
			<?php echo '<a href="javascript:reflash('.($page-1).')"';?>>
				<span class="glyphicon glyphicon glyphicon-backward"></span>
			</a>
		</li>
		<?php
		$j = $page - 2;
		if($j<1)$j = 1;
		$imax = 5;
		if($imax > $page_cnt)$imax=$page_cnt;
		for($i=1;$i<=$imax;$i++){
			if($j==$page){
				echo '<li class="active">';
			}else{
				echo '<li>';
			}
			echo '<a href="javascript:reflash('.$j.');">'.$j.'</a></li>';
			$j++;
		}
				
		?>
		<li<?php if($page==$page_cnt)echo ' class="disabled"'; ?>>
			<?php echo '<a href="javascript:reflash('.($page+1).')"';?>>
				<span class="glyphicon glyphicon glyphicon-forward"></span>
			</a>
		</li>
	</ul>
</div>
