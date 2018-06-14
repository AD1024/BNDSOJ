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

		<tr>
			<td><?php echo getUserLink($info['owner']); ?></td>
		<?php
				foreach($problems as $x){
					if(!validateUInt($x)){
						continue;
					}
					$sqlb = mysql_query("select * from submissions where problem_id = ".$x." and submitter = '".$info['owner']."' order by score desc, submit_time desc;");
					$infob = mysql_fetch_array($sqlb);
					/*if ($infob['score'] == '100') {
						echo '<td style="background-color:#38b44a">';
					} else {
						echo '<td>';
					}*/
					
					echo '<td>';
					
					if(!$infob['score']){
						$infob['score'] = 0;
					}
					
					echo '<a href="/submission/', $infob['id'], '" target="_blank" class="uoj-score">'.$infob['score'].'</a>';
					
					
					echo "</td>";
				}
		?>
			
		</tr>

		<?php
			}
		?>

	</tbody>
</table>
