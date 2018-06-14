<?php
	requirePHPLib('form');
	requirePHPLib('judger');
	
	if (!validateUInt($_GET['id'])) {
		become404Page();
	}

	if ($myUser == null) {
		redirectToLogin();
	}

	include "remote_oj/main.php";
	
	$submission['id'] = $_GET['id'];
	
	$sql = mysql_query("select * from remote_oj_submissions where id = ".$submission['id'].";");
	$info = mysql_fetch_array($sql);

	$is_ac = DB::selectCount("select count(*) from remote_oj_submissions where result = 'Accepted' and oj_name = '".$info['oj_name']."' and problem_id = ".$info['problem_id']." and submitter = '".$myUser['username']."';");

	if($is_ac < 1 and $myUser['username'] !== $info['submitter']){
		$code_show = false;
	}else{
		$code_show = true;
	}
	
	if(isSuperUser($myUser)){
		$code_show = true;
	}
	
	if(isSuperUser($myUser) or ($info['status'] === 'failed' and $myUser['username'] === $info['submitter'])){
		
		if($_POST['rejudge'] === 'true'){
			mysql_query("update remote_oj_submissions set status = 'w', result = 'Waiting' where id =".$submission['id'].";");
			header("location: ".$_SERVER['REQUEST_URI']);
		}
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

	if($info['language'] === "C++"){
		$code_type = "cpp";
	}else if($info['language'] === "C"){
		$code_type = "c";
	}else if($info['language'] === "Java"){
		$code_type = "java";
	}else if($info['language'] === "Pascal"){
		$code_type = "pascal";
	}

	$REQUIRE_LIB['shjs'] = "";

?>
<?php echoUOJPageHeader(UOJLocale::get('problems::submission').' #'.$submission['id']) ?>

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

		//$sqlu = mysql_query("select * from user_info where username ='".$info['submitter']."';");
		//$uinfo = mysql_fetch_array($sqlu);
		$uhtml = getUserLink($info['submitter']);
		if($info['result'] === 'Accepted'){
			$rhtml = '<a href="/remoteoj/submission/'.$info['id'].'" style="color: rgb(0, 204, 0);">'.$info['result'].'</a>';
		}else{
			$rhtml = '<a href="/remoteoj/submission/'.$info['id'].'" style="color: rgb(204, 0, 0);">'.$info['result'].'</a>';
		}
?>
			<tr>
				<td><a href="/remoteoj/submission/<?php echo $info['id']; ?>">#<?php echo $info['id']; ?></a></td>
				<td><?php echo '<a href="/remoteoj/problem?ojname='.$info['oj_name'].'&problemid='.$info['problem_id'].'">';?>#<?php echo $oj_names[$info['oj_name']].$info['problem_id'].".".$info['problem_name']; ?></a></td>
				<td><?php echo $uhtml; ?></td>
				<td><?php echo $rhtml; ?></td>
				<td><?php echo $info['used_time']; ?></td>
				<td><?php echo $info['used_memory']; ?></td>
				<td><a href="/remoteoj/submission/<?php echo $info['id']; ?>"><?php echo $info['language']; ?></a></td>
				<td><?php echo $info['tot_size']; ?></td>
				<td><small><?php echo $info['submit_time']; ?></small></td>
			</tr>
		</tbody>
	</table>
</div>

<?php
	if($code_show === true){
?>

<div class="panel panel-info">
	<div class="panel-heading">
		<h4 class="panel-title">answer</h4>
	</div>
	<div class="panel-body">
		<pre class="sh_sourceCode"><code class="sh_<?php echo $code_type; ?>"><?php echo htmlspecialchars(base64_decode($info['code'])); ?></code></pre>
	</div>
	<div class="panel-footer">源文件, 语言: <?php echo $info['language']; ?></div>
</div>

<?php
	}
?>

<?php if(isSuperUser($myUser) or ($info['status'] === 'failed' and $myUser['username'] === $info['submitter'])): ?>

<form action="" method="post" class="form-horizontal" id="form-rejudge">
	<input type="hidden" name="rejudge" value="true" />
	<div class="text-right">
		<button type="submit" id="button-submit-rejudge" name="submit-rejudge" value="rejudge" class="btn btn-primary">重新测试</button>
	</div>
</form>

<?php endif ?>

<?php echoUOJPageFooter() ?>
