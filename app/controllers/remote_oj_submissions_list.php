<?php
/*
	Created by dhxh
*/

	if ($myUser == null) {
		redirectToLogin();
	}

	if(!validateUInt($_GET['page']) or $_GET['page'] <= 0){
		$page = 1;
	}else{
		$page = $_GET['page'];
	}

	include "remote_oj/main.php";


	$conds = array();
	
	$q_problem_id = isset($_GET['problem_id']) ? $_GET['problem_id'] : null;
	$q_submitter = isset($_GET['submitter']) && validateUsername($_GET['submitter']) ? $_GET['submitter'] : null;
	$q_oj_name = isset($_GET['oj_name']) ? $_GET['oj_name'] : null;
	$q_result = isset($_GET['result']) ? $_GET['result'] : null;
	$q_language = isset($_GET['language']) ? $_GET['language'] : null;
	if($q_problem_id != null) {
		$conds[] = "problem_id = '".mysql_real_escape_string($q_problem_id)."'";
	}
	if($q_submitter != null) {
		$conds[] = "submitter = '$q_submitter'";
	}
	if ($q_oj_name != null) {
		$conds[] = "oj_name = '".strtolower(mysql_real_escape_string($q_oj_name))."'";
	}
	if ($q_result != null) {
		$conds[] = "result = '".mysql_real_escape_string($q_result)."'";
	}
	if ($q_language != null) {
		$conds[] = sprintf("language = '%s'", mysql_real_escape_string($q_language));
	}
	
	$html_esc_q_language = htmlspecialchars($q_language);
	$q_result = htmlspecialchars($q_result);
	$q_problem_id = htmlspecialchars($q_problem_id);
	$q_oj_name = htmlspecialchars($q_oj_name);
	
	if ($conds) {
		$cond = join($conds, ' and ');
	} else {
		$cond = '1';
	}


	$page_cnt = DB::selectCount("select count(*) from remote_oj_submissions where 1 and ".$cond.";");
	$page_cnt = floor(($page_cnt-1) / 10) + 1;

	if($page > $page_cnt){
		$page = $page_cnt;
	}

?>

<?php echoUOJPageHeader(UOJLocale::get('submissions')) ?>

<div class="hidden-xs">
	<?php if ($myUser != null): ?>
	<div class="pull-right">
		<a href="/remoteoj/submissions?submitter=<?= $myUser['username'] ?>" class="btn btn-primary btn-sm"><?= UOJLocale::get('problems::my submissions') ?></a>
	</div>
	<?php endif ?>
	<form id="form-search" class="form-inline" method="get">
		<div id="form-group-oj_name" class="form-group">
			<label for="input-oj_name" class="control-label">OJ:</label>
			<input type="text" class="form-control input-sm" name="oj_name" id="input-oj_name" value="<?= $q_oj_name ?>" maxlength="20" style="width:10em" />
		</div>
		<div id="form-group-problem_id" class="form-group">
			<label for="input-problem_id" class="control-label"><?= UOJLocale::get('problems::problem id')?>:</label>
			<input type="text" class="form-control input-sm" name="problem_id" id="input-problem_id" value="<?= $q_problem_id ?>" maxlength="6" style="width:4em" />
		</div>
		<div id="form-group-submitter" class="form-group">
			<label for="input-submitter" class="control-label"><?= UOJLocale::get('username')?>:</label>
			<input type="text" class="form-control input-sm" name="submitter" id="input-submitter" value="<?= $q_submitter ?>" maxlength="20" style="width:10em" />
		</div>
		<div id="form-group-result" class="form-group">
			<label for="input-result" class="control-label"><?= UOJLocale::get('problems::result')?>:</label>
			<input type="text" class="form-control input-sm" name="result" id="input-result" value="<?= $q_result ?>" maxlength="40" style="width:10em" />
		</div>
		<div id="form-group-language" class="form-group">
			<label for="input-language" class="control-label"><?= UOJLocale::get('problems::language')?>:</label>
			<input type="text" class="form-control input-sm" name="language" id="input-language" value="<?= $html_esc_q_language ?>" maxlength="10" style="width:8em" />
		</div>
		<button type="submit" id="submit-search" class="btn btn-default btn-sm"><?= UOJLocale::get('search')?></button>
	</form>
	<script type="text/javascript">
		$('#form-search').submit(function(e) {
			e.preventDefault();
			
			url = '/remoteoj/submissions';
			qs = [];
			$(['problem_id', 'submitter', 'result', 'oj_name', 'language']).each(function () {
				if ($('#input-' + this).val()) {
					qs.push(this + '=' + encodeURIComponent($('#input-' + this).val()));
				}
			});
			if (qs.length > 0) {
				url += '?' + qs.join('&');
			}
			location.href = url;
		});
	</script>
	<div class="top-buffer-sm"></div>
</div>

<div class="table-responsive">
	<table class="table table-bordered table-hover table-striped table-text-center">
		<thead>
			<tr>
				<th>ID</th>
				<th><?php echo UOJLocale::get('problems::problem'); ?></th>
				<th><?php echo UOJLocale::get('problems::submitter'); ?></th>
				<th><?php echo UOJLocale::get('problems::result'); ?></th>
				<th><?php echo UOJLocale::get('problems::used time'); ?></th>
				<th><?php echo UOJLocale::get('problems::used memory'); ?></th>
				<th><?php echo UOJLocale::get('problems::language'); ?></th>
				<th><?php echo UOJLocale::get('problems::file size'); ?></th>
				<th><?php echo UOJLocale::get('problems::submit time'); ?></th>
			</tr>
		</thead>
		<tbody>

<?php
	
	$sql = mysql_query("select * from remote_oj_submissions where 1 and ".$cond." order by id desc;");
	//$info = mysql_fetch_array($sql);

	$scnt = 0;

	while($info = mysql_fetch_array($sql)){
		$scnt++;
		if($scnt <= ($page-1) * 10){
			continue;
		}

		if($scnt > $page * 10){
			break;
		}

		/*if($info['status'] === "w"){
			$oj_name = $info['oj_name'];
			$oj_action = "status";
			$oj_code['id'] = $info['submission_id'];
			$oj_status = getojinfo($oj_name, $oj_action, $oj_problem_id, $oj_page_id, $oj_code);
			
			if($oj_status['isfound'] === true and $oj_status['result'] !== "w"){
				mysql_query("update remote_oj_submissions set result='".$oj_status['result']."' where id=".$info['id']);
				mysql_query("update remote_oj_submissions set used_time='".$oj_status['time']."' where id=".$info['id']);
				mysql_query("update remote_oj_submissions set used_memory='".$oj_status['memory']."' where id=".$info['id']);
				mysql_query("update remote_oj_submissions set status='".$oj_status['result']."' where id=".$info['id']);
				$info['result'] = $oj_status['result'];
				$info['used_time'] = $oj_status['time'];
				$info['used_memory'] = $oj_status['memory'];
			}
		}*/

		//$sqlu = mysql_query("select * from user_info where username ='".$info['submitter']."';");
		//$uinfo = mysql_fetch_array($sqlu);
		$uhtml = getUserLink($info['submitter']);
		if($info['result'] === 'Accepted'){
			$rhtml = '<a href="/remoteoj/submission/'.$info['id'].'" style="color: rgb(0, 204, 0);">'.$info['result'].'</a>';
		}else if($info['result'] === 'Waiting'){
			$rhtml = '<a href="/remoteoj/submission/'.$info['id'].'">'.$info['result'].'</a>';
		}else{
			$rhtml = '<a href="/remoteoj/submission/'.$info['id'].'" style="color: rgb(204, 0, 0);">'.$info['result'].'</a>';
		}
?>
			<tr>
				<td><a href="/remoteoj/submission/<?php echo $info['id']; ?>">#<?php echo $info['id']; ?></a></td>
				<td><?php echo '<a href="/remoteoj/problem?ojname='.$info['oj_name'].'&problemid='.$info['problem_id'].'">';?>#<?php echo $oj_names[$info['oj_name']].$info['problem_id'].".".$info['problem_name']; ?></a></td>
				<td><?php echo $uhtml; ?></td>
				<td><?php echo $rhtml; ?></td>
				<td><?php if($info['used_time']){echo $info['used_time'];}else{echo "/";} ?></td>
				<td><?php if($info['used_memory']){echo $info['used_memory'];}else{echo "/";} ?></td>
				<td><a href="/remoteoj/submission/<?php echo $info['id']; ?>"><?php echo $info['language']; ?></a></td>
				<td><?php if($info['tot_size']){echo $info['tot_size'];}else{echo "/";} ?></td>
				<td><small><?php echo $info['submit_time']; ?></small></td>
			</tr>
<?php
	}

	if($page_cnt == 0){
		echo '<tr><td colspan="233">无</td></tr>';
	}

?>

		</tbody>
	</table>
</div>

<?php
	if($page_cnt > 1){
?>

<div class="text-center">
	<ul class="pagination top-buffer-no bot-buffer-sm">
		<li<?php if($page==1)echo ' class="disabled"'; ?>>
			<?php echo '<a href="/remoteoj/submissions?page='.($page-1).'"';?>>
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
			echo '<a href="/remoteoj/submissions?page='.$j.'">'.$j.'</a></li>';
			$j++;
		}
				
		?>
		<li<?php if($page==$page_cnt)echo ' class="disabled"'; ?>>
			<?php echo '<a href="/remoteoj/submissions?page='.($page+1).'"';?>>
				<span class="glyphicon glyphicon glyphicon-forward"></span>
			</a>
		</li>
	</ul>
</div>

<?php	
	}
?>

<?php echoUOJPageFooter() ?>
