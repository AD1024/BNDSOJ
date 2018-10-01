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
		//become403Page();
	}
	
	$sql = mysql_query("select * from homework_index where id = ".$homework_id.";");
	$info = mysql_fetch_array($sql);
	
	$problems = explode(",", $info['problem_id']);
	
	$page_cnt = 0;
	
	foreach($problems as $x){
		if(validateUInt($x)){
			$page_cnt = 1;
			break;
		}
	}
	$cnt = 0;
?>

<?= HTML::js_src('/js/uoj.js?v=2016.8.15') ?>

<table class="table table-hover table-striped table-text-center">
	<thead>
		<tr>
			<th>用户名</th>
			<th>AC数</th>
			<?php
				foreach($problems as $x){
					if(!validateUInt($x)){
						continue;
					}
					$sql = mysql_query("select * from problems where id = ".$x.";");
					$info = mysql_fetch_array($sql);
			?>
				<th><a href="/problem/<?php echo $info['id']; ?>" target="_blank"><?php echo $info['title']; ?></a></th>
			<?php
				}
			?>
		</tr>
	</thead>
	<tbody>
		<?php
			$sql = mysql_query("select * from user_homework where homework_id = ".$homework_id." order by owner asc;");
			while($info = mysql_fetch_array($sql)){
		?>
		<?php
			$cnt = 0;
			$arr = array();
			foreach($problems as $x){
				if(!validateUInt($x)){
					continue;
				}
				$sqlb = mysql_query("select * from best_ac_submissions where problem_id = ".$x." and submitter = '".$info['owner']."';");
				$infob = mysql_fetch_array($sqlb);
				if (!empty($infob)) {
					$cnt = $cnt + 1;
				}
			}
		?>
		<tr>
			<td><?php echo getUserLink($info['owner']); ?></td>
			<td><?php echo $cnt; ?></td>
		<?php
				foreach($problems as $x){
					echo "<td>";
					$sqlb = mysql_query("select * from best_ac_submissions where problem_id = ".$x." and submitter = '".$info['owner']."';");
					$infob = mysql_fetch_array($sqlb);
					if (!empty($infob)) {
						echo '<a href="/submission/', $infob['submission_id'], '" target="_blank" class="uoj-score">'."100".'</a>';
					}
					echo "</td>";
				}
		?>
		</tr>
		<?php
			}
		?>
	</tbody>
</table>
