<?php
	if ($myUser == null) {
		become403Page();
	}

	if(!validateUInt($_GET['id'])){
		become403Page();
	}else{
		$homework_id = DB::escape($_GET['id']);
	}
	
	if(DB::selectCount("select count(*) from user_homework where homework_id = ".$homework_id." and owner = '".$myUser['username']."';") == 0){
		become403Page();
	}
	
	$sql = mysql_query("select * from homework_index where id = ".$homework_id.";");
	$info = mysql_fetch_array($sql);
	
	$problems = explode(",", $info['problem_id']);
	
	foreach($problems as $x){
		if(validateUInt($x)){
			$page_cnt = 1;
			break;
		}
	}
?>

<?= HTML::js_src('/js/uoj.js?v=2016.8.15') ?>

<div>
	<br>
	<div class="panel panel-info">
		<div class="panel-heading">
			<h3 class="panel-title"><?php echo $info['title']; ?></h3>
		</div>
		<div class="panel-body">
			<?php echo $info['msg']; ?>
		</div>
		<div class="panel-footer">完成时间：<?php echo $info['due']; ?></div>
	</div>
	<div align="center"><h3>作业试题</h3></div>
	<table class="table table-hover table-striped table-text-center">
		<thead>
			<tr>
				<th>#</th>
				<th>标题</th>
				<th>完成情况</th>
			</tr>
		</thead>
		<tbody>
			<?php
				if($page_cnt<=0){
					echo '<tr><td colspan="233">暂无试题</td></tr>';
				}else{
					foreach($problems as $x){
						if(!validateUInt($x)){
							continue;
						}
						$sql = mysql_query("select * from problems where id = ".$x.";");
						$info = mysql_fetch_array($sql);
						$cnt++;
			?>
	
			<tr>
				<td><?php echo $cnt; ?></td>
				<td><a href="/problem/<?php echo $info['id']; ?>" target="_blank"><?php echo $info['title']; ?></a></td>
				<?php
					$sql = mysql_query("select * from submissions where problem_id = ".$info['id']." and submitter = '".$myUser['username']."' order by score desc, submit_time desc;");
					$info = mysql_fetch_array($sql);
					/*if ($info['score'] == '100') {
						echo '<td style="background-color:#38b44a">';
					} else {
						echo '<td>';
					}
					if($info['score']){
						echo $info['score'];
					}else{
						echo 0;
					}*/
					
					echo '<td>';
					
					if(!$info['score']){
						$info['score'] = 0;
					}
					
					echo '<a href="/submission/', $info['id'], '" target="_blank" class="uoj-score">'.$info['score'].'</a>';
				?>
				</td>
			</tr>
	
			<?php
					}
				}
			?>
	
		</tbody>
	</table>
</div>
